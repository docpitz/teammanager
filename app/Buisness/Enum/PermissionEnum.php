<?php
namespace App\Buisness\Enum;

use BenSampo\Enum\Enum;
use Spatie\Permission\Models\Permission;


final class PermissionEnum extends Enum
{
    const UserManagement        = 1;
    const UserOwn               = 2;
    const GroupManagement       = 3;
    const EventManagement       = 4;
    const EventBookingImmediate = 5;
    const EventBookingDelayed   = 6;
    const Settings              = 7;

    public function getModel() : Permission
    {
        return Permission::findByName($this->key);
    }
}