<?php

namespace App\Models;

use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_photo',
        'latitude',
        'longitude',
        'term_and_policy',
        'google_id',
        'current_session_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(RoleEnum::ADMIN);
    }

    /**
     * Get user subscriptions.
     */
    public function userSubscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    /**
     * Get user's primary agency (if owner).
     */
    public function agency(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Agency::class);
    }

    /**
     * Get user properties.
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Get user's current active subscription.
     */
    public function currentSubscription(): ?UserSubscription
    {
        return $this->userSubscriptions()->where('active', true)->first();
    }

    /**
     * Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }
        return asset('images/img.png');
    }
}
