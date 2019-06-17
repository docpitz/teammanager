<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Buisness\Enum\PermissionEnum;
use App\Buisness\Enum\RoleEnum;

class RolesAndPermissionTableSeeder extends Seeder
{
    const NAME = 'name';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach (PermissionEnum::getInstances() as $permission)
        {
            Permission::create([self::NAME => $permission->key]);
        }

        foreach (RoleEnum::getInstances() as $role)
        {
            Role::create([self::NAME => $role->key]);
        }
        $pUserManagement = PermissionEnum::getInstance(PermissionEnum::UserManagement)->getModel();
        $pUserOwn = PermissionEnum::getInstance(PermissionEnum::UserOwn)->getModel();
        $pEventManagment = PermissionEnum::getInstance(PermissionEnum::EventManagement)->getModel();
        $pEventBookingImmediate = PermissionEnum::getInstance(PermissionEnum::EventBookingImmediate)->getModel();
        $pEventBookingDelayed = PermissionEnum::getInstance(PermissionEnum::EventBookingDelayed)->getModel();
        $pGroupManagement = PermissionEnum::getInstance(PermissionEnum::GroupManagement)->getModel();
        $pSettings = PermissionEnum::getInstance(PermissionEnum::Settings)->getModel();


        $rSuperAdmin = RoleEnum::getInstance(RoleEnum::SuperAdmin)->getModel();
        $rOrganisator = RoleEnum::getInstance(RoleEnum::Organisator)->getModel();
        $rTeamIntern = RoleEnum::getInstance(RoleEnum::TeamIntern)->getModel();
        $rTeamExtern = RoleEnum::getInstance(RoleEnum::TeamExtern)->getModel();

        $rSuperAdmin->givePermissionTo($pUserManagement,
            $pUserOwn,
            $pEventManagment,
            $pEventBookingImmediate,
            $pGroupManagement,
            $pSettings);

        $rOrganisator->givePermissionTo($pUserManagement,
            $pUserOwn,
            $pEventManagment,
            $pEventBookingImmediate,
            $pGroupManagement);

        $rTeamIntern->givePermissionTo($pUserOwn,
            $pEventBookingImmediate);

        $rTeamExtern->givePermissionTo($pUserOwn,
            $pEventBookingDelayed);
    }
}
