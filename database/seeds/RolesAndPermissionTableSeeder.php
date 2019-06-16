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

        $pUserManagement = Permission::create([self::NAME => PermissionEnum::userManagement()]);
        $pUserOwn = Permission::create([self::NAME => PermissionEnum::userOwn()]);
        $pEventManagment = Permission::create([self::NAME => PermissionEnum::eventManagement()]);
        $pEventBookingImmediate = Permission::create([self::NAME => PermissionEnum::eventBookingImmediate()]);
        $pEventBookingDelayed = Permission::create([self::NAME => PermissionEnum::eventBookingDelayed()]);
        $pGroupManagement = Permission::create([self::NAME => PermissionEnum::groupManagment()]);


        $rSuperAdmin = Role::create([self::NAME => RoleEnum::superAdmin()]);
        $rOrganisator = Role::create([self::NAME => RoleEnum::organisator()]);
        $rTeamIntern = Role::create([self::NAME => RoleEnum::teamIntern()]);
        $rTeamExtern = Role::create([self::NAME => RoleEnum::teamExtern()]);

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
