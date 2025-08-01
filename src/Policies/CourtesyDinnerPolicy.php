<?php

namespace Kaely\PmsHotel\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Kaely\PmsHotel\Models\CourtesyDinner;
use App\Models\User;

class CourtesyDinnerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any courtesy dinners.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('courtesy_dinners.view');
    }

    /**
     * Determine whether the user can view the courtesy dinner.
     */
    public function view(User $user, CourtesyDinner $courtesyDinner): bool
    {
        return $user->hasPermission('courtesy_dinners.view');
    }

    /**
     * Determine whether the user can create courtesy dinners.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('courtesy_dinners.create');
    }

    /**
     * Determine whether the user can update the courtesy dinner.
     */
    public function update(User $user, CourtesyDinner $courtesyDinner): bool
    {
        return $user->hasPermission('courtesy_dinners.edit');
    }

    /**
     * Determine whether the user can delete the courtesy dinner.
     */
    public function delete(User $user, CourtesyDinner $courtesyDinner): bool
    {
        return $user->hasPermission('courtesy_dinners.delete');
    }

    /**
     * Determine whether the user can restore the courtesy dinner.
     */
    public function restore(User $user, CourtesyDinner $courtesyDinner): bool
    {
        return $user->hasPermission('courtesy_dinners.delete');
    }

    /**
     * Determine whether the user can permanently delete the courtesy dinner.
     */
    public function forceDelete(User $user, CourtesyDinner $courtesyDinner): bool
    {
        return $user->hasPermission('courtesy_dinners.delete');
    }

    /**
     * Determine whether the user can export courtesy dinners.
     */
    public function export(User $user): bool
    {
        return $user->hasPermission('courtesy_dinners.export');
    }

    /**
     * Determine whether the user can import courtesy dinners.
     */
    public function import(User $user): bool
    {
        return $user->hasPermission('courtesy_dinners.import');
    }
}