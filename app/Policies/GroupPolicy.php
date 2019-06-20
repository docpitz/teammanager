<?php

namespace App\Policies;

use App\Buisness\Enum\PermissionEnum;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the authenticate user can manage other groups.
     *
     * @param  \App\User  $user
     * @return boolean
     */
    public function manageGroups(User $user)
    {
        return $user->hasAnyPermission(PermissionEnum::getInstance(PermissionEnum::GroupManagement)->key);
    }

}
