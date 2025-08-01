<?php

namespace Kaely\PmsHotel\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Kaely\PmsHotel\Models\RoomChange;
use App\Models\User;

class RoomChangePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any room changes.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('room_changes.view');
    }

    /**
     * Determine whether the user can view the room change.
     */
    public function view(User $user, RoomChange $roomChange): bool
    {
        return $user->hasPermission('room_changes.view');
    }

    /**
     * Determine whether the user can create room changes.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('room_changes.create');
    }

    /**
     * Determine whether the user can update the room change.
     */
    public function update(User $user, RoomChange $roomChange): bool
    {
        return $user->hasPermission('room_changes.edit');
    }

    /**
     * Determine whether the user can delete the room change.
     */
    public function delete(User $user, RoomChange $roomChange): bool
    {
        return $user->hasPermission('room_changes.delete');
    }

    /**
     * Determine whether the user can restore the room change.
     */
    public function restore(User $user, RoomChange $roomChange): bool
    {
        return $user->hasPermission('room_changes.delete');
    }

    /**
     * Determine whether the user can permanently delete the room change.
     */
    public function forceDelete(User $user, RoomChange $roomChange): bool
    {
        return $user->hasPermission('room_changes.delete');
    }

    /**
     * Determine whether the user can export room changes.
     */
    public function export(User $user): bool
    {
        return $user->hasPermission('room_changes.export');
    }

    /**
     * Determine whether the user can import room changes.
     */
    public function import(User $user): bool
    {
        return $user->hasPermission('room_changes.import');
    }
}