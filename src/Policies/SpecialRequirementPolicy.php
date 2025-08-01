<?php

namespace Kaely\PmsHotel\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Kaely\PmsHotel\Models\SpecialRequirement;
use App\Models\User;

class SpecialRequirementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any special requirements.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('special_requirements.view');
    }

    /**
     * Determine whether the user can view the special requirement.
     */
    public function view(User $user, SpecialRequirement $specialRequirement): bool
    {
        return $user->hasPermission('special_requirements.view');
    }

    /**
     * Determine whether the user can create special requirements.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('special_requirements.create');
    }

    /**
     * Determine whether the user can update the special requirement.
     */
    public function update(User $user, SpecialRequirement $specialRequirement): bool
    {
        return $user->hasPermission('special_requirements.edit');
    }

    /**
     * Determine whether the user can delete the special requirement.
     */
    public function delete(User $user, SpecialRequirement $specialRequirement): bool
    {
        return $user->hasPermission('special_requirements.delete');
    }

    /**
     * Determine whether the user can restore the special requirement.
     */
    public function restore(User $user, SpecialRequirement $specialRequirement): bool
    {
        return $user->hasPermission('special_requirements.delete');
    }

    /**
     * Determine whether the user can permanently delete the special requirement.
     */
    public function forceDelete(User $user, SpecialRequirement $specialRequirement): bool
    {
        return $user->hasPermission('special_requirements.delete');
    }

    /**
     * Determine whether the user can export special requirements.
     */
    public function export(User $user): bool
    {
        return $user->hasPermission('special_requirements.export');
    }

    /**
     * Determine whether the user can import special requirements.
     */
    public function import(User $user): bool
    {
        return $user->hasPermission('special_requirements.import');
    }
}