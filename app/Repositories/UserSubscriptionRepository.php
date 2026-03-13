<?php

namespace App\Repositories;

use App\Models\UserSubscription;

class UserSubscriptionRepository
{
    protected $model = UserSubscription::class;

    /**
     * Get user's current active subscription
     */
    public function getUserActiveSubscription(int $userId): ?UserSubscription
    {
        return $this->model::where('user_id', $userId)
            ->where('active', true)
            ->first();
    }

    /**
     * Get user subscription by ID
     */
    public function getById(int $id): ?UserSubscription
    {
        return $this->model::findOrFail($id);
    }

    /**
     * Get user subscriptions
     */
    public function getUserSubscriptions(int $userId)
    {
        return $this->model::where('user_id', $userId)
            ->with('subscription')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create user subscription
     */
    public function create(array $data): UserSubscription
    {
        return $this->model::create($data);
    }

    /**
     * Update user subscription
     */
    public function update(int $id, array $data): UserSubscription
    {
        $userSubscription = $this->getById($id);
        $userSubscription->update($data);
        return $userSubscription;
    }

    /**
     * Deactivate user subscription
     */
    public function deactivate(int $id): UserSubscription
    {
        return $this->update($id, ['active' => false]);
    }

    /**
     * Check if user subscription exists
     */
    public function userHasSubscription(int $userId, int $subscriptionId): bool
    {
        return $this->model::where('user_id', $userId)
            ->where('subscription_id', $subscriptionId)
            ->where('active', true)
            ->exists();
    }

    /**
     * Increment used properties
     */
    public function incrementUsedProperties(int $userSubscriptionId, int $count = 1): void
    {
        $this->model::where('id', $userSubscriptionId)
            ->increment('used_properties', $count);
    }

    /**
     * Decrement used properties
     */
    public function decrementUsedProperties(int $userSubscriptionId, int $count = 1): void
    {
        $this->model::where('id', $userSubscriptionId)
            ->where('used_properties', '>=', $count)
            ->decrement('used_properties', $count);
    }

    /**
     * Get user subscriptions with filters for admin panel
     */
    public function getWithFiltersForAdmin(
        ?string $status = null,
        ?int $subscriptionId = null,
        ?string $search = null,
        int $perPage = 15
    ) {
        $query = $this->model::with(['user', 'subscription'])
            ->orderByDesc('created_at');

        if ($status === 'active') {
            $query->where('active', true);
        } elseif ($status === 'inactive') {
            $query->where('active', false);
        }

        if ($subscriptionId) {
            $query->where('subscription_id', $subscriptionId);
        }

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Update subscription status
     */
    public function updateStatus(int $id, bool $active): UserSubscription
    {
        return $this->update($id, ['active' => $active]);
    }
    /**
     * Update user subscription with dates
     */
    public function updateWithDates(int $id, array $data): UserSubscription
    {
        return $this->update($id, $data);
    }
    /**
     * Deactivate other user subscriptions
     */
    public function deactivateOtherUserSubscriptions(int $userId, int $excludeSubscriptionId): void
    {
        $this->model::where('user_id', $userId)
            ->where('id', '!=', $excludeSubscriptionId)
            ->update(['active' => false]);
    }

    /**
     * Get or create user subscription
     */
    public function getOrCreateUserSubscription(int $userId, int $subscriptionId): UserSubscription
    {
        $existing = $this->model::where('user_id', $userId)
            ->where('subscription_id', $subscriptionId)
            ->first();

        if ($existing) {
            return $existing;
        }

        return $this->create([
            'user_id' => $userId,
            'subscription_id' => $subscriptionId,
            'used_properties' => 0,
            'active' => true,
        ]);
    }
}
