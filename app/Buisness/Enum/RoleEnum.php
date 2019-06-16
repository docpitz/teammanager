<?php
namespace App\Buisness\Enum;

use Spatie\Enum\Enum;
use Spatie\Permission\Models\Role;

/**
 * @method static self superAdmin()
 * @method static self organisator()
 * @method static self teamIntern()
 * @method static self teamExtern()
 */
class RoleEnum extends Enum
{
    public function getRoleModel() : Role
    {
        return Role::findByName($this);
    }


}