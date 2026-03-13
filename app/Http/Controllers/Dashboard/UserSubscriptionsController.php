<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Repositories\SubscriptionRepository;
use App\Services\UserSubscriptionService;
use Illuminate\Http\Request;

class UserSubscriptionsController extends Controller
{
    /**
     * Constructor with permission middleware.
     */
    public function __construct(
        protected UserSubscriptionService $userSubscriptionService,
        protected SubscriptionRepository $subscriptionRepository
    ) {
        $this->middleware('permission:' . PermissionEnum::MANAGE_USER_SUBSCRIPTIONS_VIEW)->only(['index']);
        $this->middleware('permission:' . PermissionEnum::MANAGE_USER_SUBSCRIPTIONS_UPDATE)->only(['updateActiveStatus', 'changeSubscription']);
    }

    /**
     * Display a listing of user subscriptions
     */
    public function index(Request $request)
    {
        $userSubscriptions = $this->userSubscriptionService->getAdminUserSubscriptions(
            $request->status ?? 'all',
            $request->filled('subscription_id') ? $request->subscription_id : null,
            $request->search,
            15
        );

        $subscriptions = $this->subscriptionRepository->getActive();

        return view('dashboard.user-subscriptions.index', [
            'userSubscriptions' => $userSubscriptions,
            'subscriptions' => $subscriptions,
            'currentStatus' => $request->status ?? 'all',
            'currentSubscriptionFilter' => $request->subscription_id,
            'searchQuery' => $request->search,
        ]);
    }

    /**
     * Update user subscription active status
     */
    public function updateActiveStatus(Request $request, $lang, $id)
    {
        try {
            // Accept both 'active' boolean and 'action' string from different sources
            if ($request->has('action')) {
                $newStatus = $request->input('action') === 'activate';
            } else {
                $newStatus = $request->boolean('active');
            }

            $this->userSubscriptionService->updateUserSubscriptionStatus($id, $newStatus);

            return response()->json([
                'success' => true,
                'message' => __('translation.messages.updated_successfully'),
                'status' => $newStatus ? 'active' : 'inactive',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('translation.messages.error_occurred')
            ], 500);
        }
    }

    /**
     * Change user's subscription to a different plan
     */
    public function changeSubscription(Request $request, $lang, $id)
    {
        try {
            $newSubscriptionId = $request->input('subscription_id');

            $this->userSubscriptionService->changeUserSubscription($id, $newSubscriptionId);

            return response()->json([
                'success' => true,
                'message' => __('translation.messages.updated_successfully'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('translation.messages.error_occurred')
            ], 500);
        }
    }
}
