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

        $pUserManagement = Permission::create([self::NAME => PermissionEnum::getInstance(PermissionEnum::UserManagement)->key]);
        $pUserOwn = Permission::create([self::NAME => PermissionEnum::getInstance(PermissionEnum::UserOwn)->key]);
        $pEventManagment = Permission::create([self::NAME => PermissionEnum::getInstance(PermissionEnum::EventManagement)->key]);
        $pEventBookingImmediate = Permission::create([self::NAME => PermissionEnum::getInstance(PermissionEnum::EventBookingImmediate)->key]);
        $pEventBookingDelayed = Permission::create([self::NAME => PermissionEnum::getInstance(PermissionEnum::EventBookingDelayed)->key]);
        $pGroupManagement = Permission::create([self::NAME => PermissionEnum::getInstance(PermissionEnum::GroupManagement)->key]);


        $rSuperAdmin = Role::create([self::NAME => RoleEnum::getInstance(RoleEnum::SuperAdmin)->key]);
        $rOrganisator = Role::create([self::NAME => RoleEnum::getInstance(RoleEnum::Organisator)->key]);
        $rTeamIntern = Role::create([self::NAME => RoleEnum::getInstance(RoleEnum::TeamIntern)->key]);
        $rTeamExtern = Role::create([self::NAME => RoleEnum::getInstance(RoleEnum::TeamExtern)->key]);

        $rSuperAdmin->givePermissionTo($pUserManagement,
            $pUserOwn,
            $pEventManagment,
            $pEventBookingImmediate,
            $pGroupManagement);

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
