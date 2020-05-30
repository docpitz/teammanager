<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Buisness\Enum\PermissionEnum;
use App\Buisness\Enum\RoleEnum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger('permission_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type', ]);

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger('role_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type', ]);

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));

        foreach (PermissionEnum::getInstances() as $permission)
        {
            Permission::create(["name" => $permission->key]);
        }

        foreach (RoleEnum::getInstances() as $role)
        {
            Role::create(["name" => $role->key]);
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
}
