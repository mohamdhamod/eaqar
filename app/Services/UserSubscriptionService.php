<?php

namespace App\Services;

use App\DTO\UserSubscriptionDTO;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use App\Repositories\SubscriptionRepository;
use App\Repositories\UserSubscriptionRepository;
use Illuminate\Support\Facades\DB;

class UserSubscriptionService
{
    public function __construct(
        protected UserSubscriptionRepository $repository,
        protected SubscriptionRepository $subscriptionRepository
    ) {}

    /**
     * Get user's current active subscription
     */
    public function getUserActiveSubscription(int $userId): ?UserSubscription
    {
        return $this->repository->getUserActiveSubscription($userId);
    }

    /**
     * Get all user subscriptions (including pending ones)
     */
    public function getUserSubscriptions(int $userId)
    {
        return $this->repository->getUserSubscriptions($userId);
    }

    /**
     * Assign free subscription to new user
     */
    public function assignFreeSubscriptionToUser(User $user): UserSubscription
    {
        // Get free subscription
        $freeSubscription = $this->subscriptionRepository->getByKey('free');
        
        if (!$freeSubscription) {
            throw new \Exception('Free subscription not found');
        }

        // Create user subscription with unlimited expiration for free plan
        $dto = new UserSubscriptionDTO(
            user_id: $user->id,
            subscription_id: $freeSubscription->id,
            used_properties: 0,
            started_at: now()->toDateTimeString(),
            expires_at: null,  // Unlimited for free plan
            active: true,
        );

        return $this->repository->create($dto->toCreateArray());
    }

    /**
     * Check if user can add properties
     */
    public function canUserAddProperties(int $userId): bool
    {
        $userSubscription = $this->getUserActiveSubscription($userId);
        
        if (!$userSubscription) {
            return false;
        }

        return $userSubscription->canAddProperties();
    }

    /**
     * Get user's remaining properties quota
     */
    public function getUserRemainingQuota(int $userId): int
    {
        $userSubscription = $this->getUserActiveSubscription($userId);
        
        if (!$userSubscription) {
            return 0;
        }

        return $userSubscription->remainingPropertiesQuota();
    }

    /**
     * Increment user properties count
     */
    public function incrementUsedProperties(int $userId, int $count = 1): void
    {
        $userSubscription = $this->getUserActiveSubscription($userId);
        
        if ($userSubscription) {
            $this->repository->incrementUsedProperties($userSubscription->id, $count);
        }
    }

    /**
     * Decrement user properties count
     */
    public function decrementUsedProperties(int $userId, int $count = 1): void
    {
        $userSubscription = $this->getUserActiveSubscription($userId);
        
        if ($userSubscription) {
            $this->repository->decrementUsedProperties($userSubscription->id, $count);
        }
    }

    /**
     * Get all subscriptions with user's current subscription highlighted
     */
    public function getSubscriptionsForUser(int $userId)
    {
        $currentSubscription = $this->getUserActiveSubscription($userId);
        $subscriptions = $this->subscriptionRepository->getActive();

        return $subscriptions->map(function ($subscription) use ($currentSubscription) {
            $subscription->is_current = $currentSubscription && $currentSubscription->subscription_id === $subscription->id;
            return $subscription;
        });
    }

    /**
     * Upgrade user subscription
     */
    public function upgradeUserSubscription(int $userId, int $newSubscriptionId): UserSubscription
    {
        try {
            DB::beginTransaction();

            // Get new subscription
            $newSubscription = $this->subscriptionRepository->getById($newSubscriptionId);
            
            // Get current subscription for reference
            $currentSubscription = $this->getUserActiveSubscription($userId);

            // Create new subscription without deactivating the current one
            // This keeps the history of all subscriptions
            // Subscription starts as inactive - admin activates it after verifying payment
            $dto = new UserSubscriptionDTO(
                user_id: $userId,
                subscription_id: $newSubscriptionId,
                used_properties: $currentSubscription?->used_properties ?? 0,
                active: false,
            );

            $userSubscription = $this->repository->create($dto->toCreateArray());

            DB::commit();

            return $userSubscription;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get user subscriptions for admin panel with filters
     */
    public function getAdminUserSubscriptions(
        ?string $status = null,
        ?int $subscriptionId = null,
        ?string $search = null,
        int $perPage = 15
    ) {
        return $this->repository->getWithFiltersForAdmin($status, $subscriptionId, $search, $perPage);
    }

    /**
     * Update user subscription status (admin operation)
     */
    public function updateUserSubscriptionStatus(int $userSubscriptionId, bool $active): UserSubscription
    {
        try {
            DB::beginTransaction();

            $userSubscription = $this->repository->getById($userSubscriptionId);
            
            $updateData = ['active' => $active];
            
            if ($active) {
                // When activating, calculate expiration date based on subscription duration
                $subscription = $userSubscription->subscription;
                if ($subscription && $subscription->duration_days > 0) {
                    $expiresAt = now()->addDays($subscription->duration_days);
                    $updateData['expires_at'] = $expiresAt->toDateTimeString();
                }
                
                // Also set started_at if not already set
                if (!$userSubscription->started_at) {
                    $updateData['started_at'] = now()->toDateTimeString();
                }
                
                // When activating, deactivate other subscriptions for this user
                $this->repository->deactivateOtherUserSubscriptions($userSubscription->user_id, $userSubscriptionId);
            }

            $updated = $this->repository->update($userSubscriptionId, $updateData);

            DB::commit();

            return $updated;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Change user subscription to a different plan (admin operation)
     */
    public function changeUserSubscription(int $userSubscriptionId, int $newSubscriptionId): UserSubscription
    {
        try {
            DB::beginTransaction();

            $userSubscription = $this->repository->getById($userSubscriptionId);
            
            // Validate new subscription exists
            $newSubscription = $this->subscriptionRepository->getById($newSubscriptionId);
            if (!$newSubscription) {
                throw new \Exception('Subscription not found');
            }

            // Deactivate current subscription
            $this->repository->deactivate($userSubscriptionId);

            // Get or create subscription for user
            $changedSubscription = $this->repository->getOrCreateUserSubscription(
                $userSubscription->user_id,
                $newSubscriptionId
            );

            // Ensure it's active
            if (!$changedSubscription->active) {
                $this->repository->updateStatus($changedSubscription->id, true);
            }

            DB::commit();

            return $changedSubscription;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
