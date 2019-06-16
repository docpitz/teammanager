<?php
namespace App\Buisness\Enum;

use Spatie\Enum\Enum;
use Spatie\Permission\Models\Permission;

/**
 * @method static self userManagement()
 * @method static self userOwn()
 * @method static self groupManagment()
 * @method static self eventManagement()
 * @method static self eventBookingImmediate()
 * @method static self eventBookingDelayed()
 */
class PermissionEnum extends Enum
{
    public function getModel() : Permission
    {
        return Permission::findByName($this);
    }
}