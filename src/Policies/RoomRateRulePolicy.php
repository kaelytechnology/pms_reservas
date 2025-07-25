<?php

namespace Kaely\PmsHotel\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Kaely\PmsHotel\Models\RoomRateRule;
use App\Models\User;

class RoomRateRulePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any room rate rules.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('room_rate_rules.view');
    }

    /**
     * Determine whether the user can view the room rate rule.
     */
    public function view(User $user, RoomRateRule $roomRateRule): bool
    {
        return $user->hasPermission('room_rate_rules.view');
    }

    /**
     * Determine whether the user can create room rate rules.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('room_rate_rules.create');
    }

    /**
     * Determine whether the user can update the room rate rule.
     */
    public function update(User $user, RoomRateRule $roomRateRule): bool
    {
        return $user->hasPermission('room_rate_rules.edit');
    }

    /**
     * Determine whether the user can delete the room rate rule.
     */
    public function delete(User $user, RoomRateRule $roomRateRule): bool
    {
        return $user->hasPermission('room_rate_rules.delete');
    }

    /**
     * Determine whether the user can restore the room rate rule.
     */
    public function restore(User $user, RoomRateRule $roomRateRule): bool
    {
        return $user->hasPermission('room_rate_rules.delete');
    }

    /**
     * Determine whether the user can permanently delete the room rate rule.
     */
    public function forceDelete(User $user, RoomRateRule $roomRateRule): bool
    {
        return $user->hasPermission('room_rate_rules.delete');
    }

    /**
     * Determine whether the user can export room rate rules.
     */
    public function export(User $user): bool
    {
        return $user->hasPermission('room_rate_rules.export');
    }

    /**
     * Determine whether the user can import room rate rules.
     */
    public function import(User $user): bool
    {
        return $user->hasPermission('room_rate_rules.import');
    }

    /**
     * Determine whether the user can edit the room rate rule.
     * Alias for update method to match controller usage.
     */
    public function edit(User $user, RoomRateRule $roomRateRule): bool
    {
        return $this->update($user, $roomRateRule);
    }
}