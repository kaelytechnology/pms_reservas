<?php

namespace Kaely\PmsHotel\Policies;

use Illuminate\Auth\Access\Response;
use Kaely\PmsHotel\Models\Restaurant;
use App\Models\User;

class RestaurantPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('restaurants.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Restaurant $restaurant): bool
    {
        return $user->hasPermission('restaurants.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('restaurants.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Restaurant $restaurant): bool
    {
        return $user->hasPermission('restaurants.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Restaurant $restaurant): bool
    {
        return $user->hasPermission('restaurants.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Restaurant $restaurant): bool
    {
        return $user->hasPermission('restaurants.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Restaurant $restaurant): bool
    {
        return $user->hasPermission('restaurants.delete');
    }

    /**
     * Determine whether the user can export models.
     */
    public function export(User $user): bool
    {
        return $user->hasPermission('restaurants.export');
    }

    /**
     * Determine whether the user can import models.
     */
    public function import(User $user): bool
    {
        return $user->hasPermission('restaurants.import');
    }
}