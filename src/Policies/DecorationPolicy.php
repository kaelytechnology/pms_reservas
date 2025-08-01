<?php

namespace Kaely\PmsHotel\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Kaely\PmsHotel\Models\Decoration;
use App\Models\User;

class DecorationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any decorations.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('decorations.view');
    }

    /**
     * Determine whether the user can view the decoration.
     */
    public function view(User $user, Decoration $decoration): bool
    {
        return $user->hasPermission('decorations.view');
    }

    /**
     * Determine whether the user can create decorations.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('decorations.create');
    }

    /**
     * Determine whether the user can update the decoration.
     */
    public function update(User $user, Decoration $decoration): bool
    {
        return $user->hasPermission('decorations.edit');
    }

    /**
     * Determine whether the user can delete the decoration.
     */
    public function delete(User $user, Decoration $decoration): bool
    {
        return $user->hasPermission('decorations.delete');
    }

    /**
     * Determine whether the user can restore the decoration.
     */
    public function restore(User $user, Decoration $decoration): bool
    {
        return $user->hasPermission('decorations.delete');
    }

    /**
     * Determine whether the user can permanently delete the decoration.
     */
    public function forceDelete(User $user, Decoration $decoration): bool
    {
        return $user->hasPermission('decorations.delete');
    }

    /**
     * Determine whether the user can export decorations.
     */
    public function export(User $user): bool
    {
        return $user->hasPermission('decorations.export');
    }

    /**
     * Determine whether the user can import decorations.
     */
    public function import(User $user): bool
    {
        return $user->hasPermission('decorations.import');
    }
}