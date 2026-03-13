<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Repositories\SubscriptionRepository;
use App\Services\AgencyService;
use App\Services\UserSubscriptionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class SubscriptionsController extends Controller
{
    public function __construct(
        protected SubscriptionRepository $subscriptionRepository,
        protected UserSubscriptionService $userSubscriptionService,
        protected AgencyService $agencyService
    ) {}

    /**
     * Check if user's agency is approved
     */
    private function checkAgencyApproved(): bool|RedirectResponse
    {
        $agency = $this->agencyService->getUserAgency(Auth::id());

        if (!$agency || !$agency->is_active) {
            return redirect()->route('agency.index')
                ->with('error', __('translation.agency.properties_waiting_approval'));
        }

        return true;
    }

    /**
     * Show all subscription plans
     */
    public function index(): View|RedirectResponse
    {
        // Check if user's agency is approved
        $agencyCheck = $this->checkAgencyApproved();
        if ($agencyCheck !== true) {
            return $agencyCheck;
        }

        $subscriptions = $this->subscriptionRepository->getActive();
        $currentUserSubscription = null;
        $userSubscriptions = collect();

        if (Auth::check()) {
            $currentUserSubscription = $this->userSubscriptionService->getUserActiveSubscription(Auth::id());
            // Get all user subscriptions (including pending ones)
            $userSubscriptions = $this->userSubscriptionService->getUserSubscriptions(Auth::id());
        }

        // Mark current subscription and check for pending subscriptions
        $subscriptions = $subscriptions->map(function ($subscription) use ($currentUserSubscription, $userSubscriptions) {
            $subscription->is_current = $currentUserSubscription && $subscription->id === $currentUserSubscription->subscription_id;
            
            // Check if user has a pending subscription for this plan
            $pendingSubscription = $userSubscriptions->firstWhere(function($userSub) use ($subscription) {
                return $userSub->subscription_id === $subscription->id && !$userSub->active;
            });
            $subscription->is_pending = !is_null($pendingSubscription);
            
            return $subscription;
        });

        // Hide free plan completely from subscriptions page
        $subscriptions = $subscriptions->filter(fn($sub) => $sub->key !== 'free');

        return view('pages.subscriptions.index', [
            'subscriptions' => $subscriptions,
            'currentUserSubscription' => $currentUserSubscription,
        ]);
    }

    /**
     * Upgrade user subscription
     */
    public function upgrade(): RedirectResponse
    {
        // Check if user's agency is approved
        $agencyCheck = $this->checkAgencyApproved();
        if ($agencyCheck !== true) {
            return $agencyCheck;
        }

        $subscriptionId = request()->input('subscription_id');

        $subscription = $this->subscriptionRepository->getById($subscriptionId);

        if (!$subscription) {
            return redirect()->back()->with('error', __('translation.subscription.not_found'));
        }

        // Check if user is upgrading to same subscription
        $currentSubscription = $this->userSubscriptionService->getUserActiveSubscription(Auth::id());
        
        if ($currentSubscription && $currentSubscription->subscription_id === $subscriptionId) {
            return redirect()->back()->with('info', __('translation.subscription.already_subscribed'));
        }

        try {
            $this->userSubscriptionService->upgradeUserSubscription(Auth::id(), $subscriptionId);

            return redirect()->back()->with('success', __('translation.subscription.upgraded_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('translation.subscription.upgrade_failed'));
        }
    }
}
