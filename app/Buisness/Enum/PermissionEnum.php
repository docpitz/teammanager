<?php
namespace App\Buisness\Enum;

use BenSampo\Enum\Enum;
use Spatie\Permission\Models\Permission;


final class PermissionEnum extends Enum
{
    const UserManagement        = 0;
    const UserOwn               = 1;
    const GroupManagement       = 2;
    const EventManagement       = 3;
    const EventBookingImmediate = 4;
    const EventBookingDelayed   = 5;

    public function getModel() : Permission
    {
        return Permission::findByName($this->key);
    }
}