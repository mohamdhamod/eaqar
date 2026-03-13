<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $fillable = [
        'user_id',
        'agency_id',
        'city_id',
        'operation_type_id',
        'property_type_id',
        'currency_id',
        'slug',
        'price',
        'area',
        'rooms',
        'bathrooms',
        'floor',
        'total_floors',
        'building_age',
        'latitude',
        'longitude',
        'status',
        'is_featured',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'area'        => 'decimal:2',
        'latitude'    => 'decimal:7',
        'longitude'   => 'decimal:7',
        'is_featured' => 'boolean',
        'active'      => 'boolean',
    ];

    public array $translatedAttributes = [
        'title',
        'description',
        'address',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function city()
    {
        return $this->belongsTo(Configuration::class, 'city_id');
    }

    public function operationType()
    {
        return $this->belongsTo(Configuration::class, 'operation_type_id');
    }

    public function propertyType()
    {
        return $this->belongsTo(Configuration::class, 'property_type_id');
    }

    public function currency()
    {
        return $this->belongsTo(Configuration::class, 'currency_id');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    public function mainImage()
    {
        return $this->hasOne(PropertyImage::class)->where('is_main', true);
    }

    public function favorites()
    {
        return $this->hasMany(PropertyFavorite::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('active', true)->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeForSale($query)
    {
        return $query->whereHas('operationType', function ($q) {
            $q->where('key', 'operation_type')->where('code', 'sale');
        });
    }

    public function scopeForRent($query)
    {
        return $query->whereHas('operationType', function ($q) {
            $q->where('key', 'operation_type')->where('code', 'rent');
        });
    }

    public function scopeInCity($query, int $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    public function scopeOfType($query, int $propertyTypeId)
    {
        return $query->where('property_type_id', $propertyTypeId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('is_featured')->orderBy('sort_order')->orderByDesc('created_at');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
