<?php

namespace Kaely\PmsHotel\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Kaely\PmsHotel\Models\Beverage;
use App\Models\User;

class BeveragePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any beverages.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('beverages.view');
    }

    /**
     * Determine whether the user can view the beverage.
     */
    public function view(User $user, Beverage $beverage): bool
    {
        return $user->hasPermission('beverages.view');
    }

    /**
     * Determine whether the user can create beverages.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('beverages.create');
    }

    /**
     * Determine whether the user can update the beverage.
     */
    public function update(User $user, Beverage $beverage): bool
    {
        return $user->hasPermission('beverages.edit');
    }

    /**
     * Determine whether the user can delete the beverage.
     */
    public function delete(User $user, Beverage $beverage): bool
    {
        return $user->hasPermission('beverages.delete');
    }

    /**
     * Determine whether the user can restore the beverage.
     */
    public function restore(User $user, Beverage $beverage): bool
    {
        return $user->hasPermission('beverages.delete');
    }

    /**
     * Determine whether the user can permanently delete the beverage.
     */
    public function forceDelete(User $user, Beverage $beverage): bool
    {
        return $user->hasPermission('beverages.delete');
    }

    /**
     * Determine whether the user can export beverages.
     */
    public function export(User $user): bool
    {
        return $user->hasPermission('beverages.export');
    }

    /**
     * Determine whether the user can import beverages.
     */
    public function import(User $user): bool
    {
        return $user->hasPermission('beverages.import');
    }
}