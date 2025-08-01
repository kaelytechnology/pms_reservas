<?php

namespace Kaely\PmsHotel\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Kaely\PmsHotel\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any reservations.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('reservations.view');
    }

    /**
     * Determine whether the user can view the reservation.
     */
    public function view(User $user, Reservation $reservation): bool
    {
        return $user->hasPermission('reservations.view');
    }

    /**
     * Determine whether the user can create reservations.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('reservations.create');
    }

    /**
     * Determine whether the user can update the reservation.
     */
    public function update(User $user, Reservation $reservation): bool
    {
        return $user->hasPermission('reservations.edit');
    }

    /**
     * Determine whether the user can delete the reservation.
     */
    public function delete(User $user, Reservation $reservation): bool
    {
        return $user->hasPermission('reservations.delete');
    }

    /**
     * Determine whether the user can restore the reservation.
     */
    public function restore(User $user, Reservation $reservation): bool
    {
        return $user->hasPermission('reservations.delete');
    }

    /**
     * Determine whether the user can permanently delete the reservation.
     */
    public function forceDelete(User $user, Reservation $reservation): bool
    {
        return $user->hasPermission('reservations.delete');
    }

    /**
     * Determine whether the user can export reservations.
     */
    public function export(User $user): bool
    {
        return $user->hasPermission('reservations.export');
    }

    /**
     * Determine whether the user can import reservations.
     */
    public function import(User $user): bool
    {
        return $user->hasPermission('reservations.import');
    }
}