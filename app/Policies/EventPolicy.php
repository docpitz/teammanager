<?php

namespace App\Policies;

use App\Buisness\Enum\PermissionEnum;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the authenticate user can manage other users.
     *
     * @param  \App\User  $user
     * @return boolean
     */
    public function manageUsers(User $user)
    {
        return $user->hasAnyPermission(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key);
    }

}
