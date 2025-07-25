<?php

namespace Kaely\PmsHotel\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Kaely\PmsHotel\Models\Food;
use App\Models\User;

class FoodPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any foods.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('foods.view');
    }

    /**
     * Determine whether the user can view the food.
     */
    public function view(User $user, Food $food): bool
    {
        return $user->hasPermission('foods.view');
    }

    /**
     * Determine whether the user can create foods.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('foods.create');
    }

    /**
     * Determine whether the user can update the food.
     */
    public function update(User $user, Food $food): bool
    {
        return $user->hasPermission('foods.edit');
    }

    /**
     * Determine whether the user can delete the food.
     */
    public function delete(User $user, Food $food): bool
    {
        return $user->hasPermission('foods.delete');
    }

    /**
     * Determine whether the user can restore the food.
     */
    public function restore(User $user, Food $food): bool
    {
        return $user->hasPermission('foods.delete');
    }

    /**
     * Determine whether the user can permanently delete the food.
     */
    public function forceDelete(User $user, Food $food): bool
    {
        return $user->hasPermission('foods.delete');
    }

    /**
     * Determine whether the user can export foods.
     */
    public function export(User $user): bool
    {
        return $user->hasPermission('foods.export');
    }

    /**
     * Determine whether the user can import foods.
     */
    public function import(User $user): bool
    {
        return $user->hasPermission('foods.import');
    }
}