<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    /**
     * Determine whether the user can view any properties.
     */
    public function viewAny(User $user): bool
    {
        return true; // Anyone can view properties
    }

    /**
     * Determine whether the user can view the property.
     */
    public function view(User $user, Property $property): bool
    {
        return true; // Anyone can view a property
    }

    /**
     * Determine whether the user can create properties.
     */
    public function create(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    /**
     * Determine whether the user can update the property.
     */
    public function update(User $user, Property $property): bool
    {
        // User must be the property owner or agency manager
        return $user->id === $property->user_id || 
               ($property->agency_id && $user->agencies()->where('agencies.id', $property->agency_id)->exists());
    }

    /**
     * Determine whether the user can delete the property.
     */
    public function delete(User $user, Property $property): bool
    {
        // User must be the property owner or agency manager
        return $user->id === $property->user_id || 
               ($property->agency_id && $user->agencies()->where('agencies.id', $property->agency_id)->exists());
    }

    /**
     * Determine whether the user can restore the property.
     */
    public function restore(User $user, Property $property): bool
    {
        return $this->delete($user, $property);
    }

    /**
     * Determine whether the user can permanently delete the property.
     */
    public function forceDelete(User $user, Property $property): bool
    {
        return $this->delete($user, $property);
    }
}
