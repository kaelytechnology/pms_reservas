<?php

namespace Kaely\PmsHotel\Policies;

use Illuminate\Auth\Access\Response;
use Kaely\PmsHotel\Models\Dish;
use App\Models\User;

class DishPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('dishes.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Dish $dish): bool
    {
        return $user->hasPermission('dishes.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('dishes.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Dish $dish): bool
    {
        return $user->hasPermission('dishes.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dish $dish): bool
    {
        return $user->hasPermission('dishes.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Dish $dish): bool
    {
        return $user->hasPermission('dishes.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Dish $dish): bool
    {
        return $user->hasPermission('dishes.delete');
    }

    /**
     * Determine whether the user can export models.
     */
    public function export(User $user): bool
    {
        return $user->hasPermission('dishes.export');
    }

    /**
     * Determine whether the user can import models.
     */
    public function import(User $user): bool
    {
        return $user->hasPermission('dishes.import');
    }
}