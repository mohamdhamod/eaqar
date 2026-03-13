<?php

namespace App\Actions;

use App\Models\User;
use App\Services\UserSubscriptionService;
use Illuminate\Support\Facades\Redirect;

class ValidatePropertyCreation
{
    public function __construct(
        protected UserSubscriptionService $userSubscriptionService
    ) {}

    /**
     * Validate if user can create a property
     * Returns true if allowed, or a redirect response if not
     */
    public function validate(User $user): bool|object
    {
        // Check if user has active subscription
        if (!$user->currentSubscription()) {
            return Redirect::to(route('subscriptions.index'))
                ->with('error', __('translation.subscription.no_active_subscription'));
        }

        // Check if user has reached property limit
        if (!$this->userSubscriptionService->canUserAddProperties($user->id)) {
            $remaining = $this->userSubscriptionService->getUserRemainingQuota($user->id);
            
            return Redirect::to(route('subscriptions.index'))
                ->with('error', __('translation.subscription.limit_reached'));
        }

        return true;
    }

    /**
     * Get user's current quota information
     */
    public function getQuotaInfo(User $user): array
    {
        $subscription = $user->currentSubscription();

        if (!$subscription) {
            return [
                'has_subscription' => false,
                'used' => 0,
                'limit' => 0,
                'remaining' => 0,
            ];
        }

        return [
            'has_subscription' => true,
            'used' => $subscription->used_properties,
            'limit' => $subscription->subscription->max_properties,
            'remaining' => $subscription->remainingPropertiesQuota(),
            'can_add' => $subscription->canAddProperties(),
        ];
    }
}
