<?php

namespace Kaely\PmsHotel\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Kaely\PmsHotel\Models\Dessert;
use App\Models\User;

class DessertPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any desserts.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('desserts.view');
    }

    /**
     * Determine whether the user can view the dessert.
     */
    public function view(User $user, Dessert $dessert): bool
    {
        return $user->hasPermission('desserts.view');
    }

    /**
     * Determine whether the user can create desserts.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('desserts.create');
    }

    /**
     * Determine whether the user can update the dessert.
     */
    public function update(User $user, Dessert $dessert): bool
    {
        return $user->hasPermission('desserts.edit');
    }

    /**
     * Determine whether the user can delete the dessert.
     */
    public function delete(User $user, Dessert $dessert): bool
    {
        return $user->hasPermission('desserts.delete');
    }

    /**
     * Determine whether the user can restore the dessert.
     */
    public function restore(User $user, Dessert $dessert): bool
    {
        return $user->hasPermission('desserts.delete');
    }

    /**
     * Determine whether the user can permanently delete the dessert.
     */
    public function forceDelete(User $user, Dessert $dessert): bool
    {
        return $user->hasPermission('desserts.delete');
    }

    /**
     * Determine whether the user can export desserts.
     */
    public function export(User $user): bool
    {
        return $user->hasPermission('desserts.export');
    }

    /**
     * Determine whether the user can import desserts.
     */
    public function import(User $user): bool
    {
        return $user->hasPermission('desserts.import');
    }
}