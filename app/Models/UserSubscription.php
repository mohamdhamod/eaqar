<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'used_properties',
        'started_at',
        'expires_at',
        'active',
    ];

    protected $casts = [
        'used_properties' => 'integer',
        'active' => 'boolean',
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the User associated with this subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Subscription associated with this user subscription.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Check if the user has reached the property limit.
     */
    public function hasReachedLimit(): bool
    {
        if ($this->subscription->max_properties === 0) {
            return false; // 0 means unlimited
        }

        return $this->used_properties >= $this->subscription->max_properties;
    }

    /**
     * Check if the user can add more properties.
     */
    public function canAddProperties(): bool
    {
        return !$this->hasReachedLimit();
    }

    /**
     * Get remaining properties quota.
     */
    public function remainingPropertiesQuota(): int
    {
        if ($this->subscription->max_properties === 0) {
            return PHP_INT_MAX; // Unlimited
        }

        return max(0, $this->subscription->max_properties - $this->used_properties);
    }

    /**
     * Increment used properties.
     */
    public function incrementUsedProperties(int $count = 1): void
    {
        $this->increment('used_properties', $count);
    }

    /**
     * Decrement used properties.
     */
    public function decrementUsedProperties(int $count = 1): void
    {
        $this->decrement('used_properties', $count);
    }

    /**
     * Scope to get active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope to get user's current subscription.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId)->active();
    }
}
