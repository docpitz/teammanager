<?php
namespace App\Buisness\Enum;

use BenSampo\Enum\Enum;
use Spatie\Permission\Models\Role;

/**
 * @method static self superAdmin()
 * @method static self organisator()
 * @method static self teamIntern()
 * @method static self teamExtern()
 */
class RoleEnum extends Enum
{
    const SuperAdmin    = 1;
    const Organisator   = 2;
    const TeamIntern    = 3;
    const TeamExtern    = 4;

    public function getModel() : Role
    {
        return Role::findByName($this->key);
    }

}