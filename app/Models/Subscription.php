<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Subscription extends Model implements TranslatableContract
{
    use Translatable;

    protected $fillable = [
        'key',
        'icon',
        'color',
        'active',
        'sort_order',
        'slug',
        'price',
        'duration_days',
        'max_properties',
        'features',
        'currency_id',
    ];

    protected $casts = [
        'active' => 'boolean',
        'features' => 'json',
        'price' => 'decimal:2',
    ];

    public array $translatedAttributes = [
        'name',
        'description',
    ];

    /**
     * Get the Currency Configuration associated with this subscription.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Configuration::class, 'currency_id');
    }

    /**
     * Scope to get only active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope to order by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get the route key name for Subscription.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Boot method for model events.
     */
    protected static function booted()
    {
        // Generate slug when creating or updating
        static::creating(function (Subscription $subscription) {
            if (empty($subscription->slug)) {
                $base = $subscription->translateOrNew(app()->getLocale())->name ?? 'subscription-' . $subscription->id;
                $subscription->slug = Str::slug($base);
            }
        });

        static::saved(function (Subscription $subscription) {
            if (empty($subscription->slug)) {
                $subscription->slug = 'subscription-' . $subscription->id;
                $subscription->saveQuietly();
            }
        });
    }
}

