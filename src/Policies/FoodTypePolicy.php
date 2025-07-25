<?php

namespace Kaely\PmsHotel\Policies;

use Illuminate\Auth\Access\Response;
use Kaely\PmsHotel\Models\FoodType;
use App\Models\User;

class FoodTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('food_types.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FoodType $foodType): bool
    {
        return $user->hasPermission('food_types.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('food_types.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FoodType $foodType): bool
    {
        return $user->hasPermission('food_types.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FoodType $foodType): bool
    {
        return $user->hasPermission('food_types.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FoodType $foodType): bool
    {
        return $user->hasPermission('food_types.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FoodType $foodType): bool
    {
        return $user->hasPermission('food_types.delete');
    }

    /**
     * Determine whether the user can export models.
     */
    public function export(User $user): bool
    {
        return $user->hasPermission('food_types.export');
    }

    /**
     * Determine whether the user can import models.
     */
    public function import(User $user): bool
    {
        return $user->hasPermission('food_types.import');
    }
}