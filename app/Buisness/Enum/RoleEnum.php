<?php
namespace App\Buisness\Enum;

use BenSampo\Enum\Enum;
use Spatie\Permission\Models\Role;

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

    public function getFormattedName() : String
    {
        $roleName = ucwords($this->description);
        $data = preg_split('/(?=[A-Z])/', $roleName);
        return implode(' ', $data);
    }

}