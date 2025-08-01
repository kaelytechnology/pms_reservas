<?php

namespace Kaely\PmsHotel\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Kaely\PmsHotel\Models\RestaurantAvailability;
use App\Models\User;

class RestaurantAvailabilityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any restaurant availabilities.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('restaurant_availabilities.view');
    }

    /**
     * Determine whether the user can view the restaurant availability.
     */
    public function view(User $user, RestaurantAvailability $restaurantAvailability): bool
    {
        return $user->hasPermission('restaurant_availabilities.view');
    }

    /**
     * Determine whether the user can create restaurant availabilities.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('restaurant_availabilities.create');
    }

    /**
     * Determine whether the user can update the restaurant availability.
     */
    public function update(User $user, RestaurantAvailability $restaurantAvailability): bool
    {
        return $user->hasPermission('restaurant_availabilities.edit');
    }

    /**
     * Determine whether the user can delete the restaurant availability.
     */
    public function delete(User $user, RestaurantAvailability $restaurantAvailability): bool
    {
        return $user->hasPermission('restaurant_availabilities.delete');
    }

    /**
     * Determine whether the user can restore the restaurant availability.
     */
    public function restore(User $user, RestaurantAvailability $restaurantAvailability): bool
    {
        return $user->hasPermission('restaurant_availabilities.delete');
    }

    /**
     * Determine whether the user can permanently delete the restaurant availability.
     */
    public function forceDelete(User $user, RestaurantAvailability $restaurantAvailability): bool
    {
        return $user->hasPermission('restaurant_availabilities.delete');
    }

    /**
     * Determine whether the user can export restaurant availabilities.
     */
    public function export(User $user): bool
    {
        return $user->hasPermission('restaurant_availabilities.export');
    }

    /**
     * Determine whether the user can import restaurant availabilities.
     */
    public function import(User $user): bool
    {
        return $user->hasPermission('restaurant_availabilities.import');
    }
}