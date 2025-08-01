<?php

namespace Kaely\PmsHotel\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Kaely\PmsHotel\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any departments.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('departments.view');
    }

    /**
     * Determine whether the user can view the department.
     */
    public function view(User $user, Department $department): bool
    {
        return $user->hasPermission('departments.view');
    }

    /**
     * Determine whether the user can create departments.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('departments.create');
    }

    /**
     * Determine whether the user can update the department.
     */
    public function update(User $user, Department $department): bool
    {
        return $user->hasPermission('departments.edit');
    }

    /**
     * Determine whether the user can delete the department.
     */
    public function delete(User $user, Department $department): bool
    {
        return $user->hasPermission('departments.delete');
    }

    /**
     * Determine whether the user can restore the department.
     */
    public function restore(User $user, Department $department): bool
    {
        return $user->hasPermission('departments.delete');
    }

    /**
     * Determine whether the user can permanently delete the department.
     */
    public function forceDelete(User $user, Department $department): bool
    {
        return $user->hasPermission('departments.delete');
    }

    /**
     * Determine whether the user can export departments.
     */
    public function export(User $user): bool
    {
        return $user->hasPermission('departments.export');
    }

    /**
     * Determine whether the user can import departments.
     */
    public function import(User $user): bool
    {
        return $user->hasPermission('departments.import');
    }
}