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
    const SuperAdmin    = 0;
    const Organisator   = 1;
    const TeamIntern    = 2;
    const TeamExtern    = 3;

    public function getModel() : Role
    {
        return Role::findByName($this->key);
    }

}