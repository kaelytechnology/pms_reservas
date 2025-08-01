<?php

namespace Kaely\PmsHotel\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Kaely\PmsHotel\Models\Event;
use App\Models\User;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any events.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('events.view');
    }

    /**
     * Determine whether the user can view the event.
     */
    public function view(User $user, Event $event): bool
    {
        return $user->hasPermission('events.view');
    }

    /**
     * Determine whether the user can create events.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('events.create');
    }

    /**
     * Determine whether the user can update the event.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->hasPermission('events.edit');
    }

    /**
     * Determine whether the user can delete the event.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->hasPermission('events.delete');
    }

    /**
     * Determine whether the user can restore the event.
     */
    public function restore(User $user, Event $event): bool
    {
        return $user->hasPermission('events.delete');
    }

    /**
     * Determine whether the user can permanently delete the event.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        return $user->hasPermission('events.delete');
    }

    /**
     * Determine whether the user can export events.
     */
    public function export(User $user): bool
    {
        return $user->hasPermission('events.export');
    }

    /**
     * Determine whether the user can import events.
     */
    public function import(User $user): bool
    {
        return $user->hasPermission('events.import');
    }
}