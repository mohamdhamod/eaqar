<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Agency extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'logo',
        'address',
        'phone',
        'email',
        'website',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    /**
     * Get the user that owns the agency.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the properties listed by this agency.
     */
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Scope: Get active agencies only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get agencies for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
