<?php

use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Buisness\Enum\RoleEnum;

class CreateAdminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $system = new User([
            'username' => 'System',
            'firstname' => 'System',
            'surname' => 'System',
            'email' => config('app.admin_email'),
            'email_optional' => null,
            'email_verified_at' => now(),
            'password' => 'loginnotpossible',
            'created_at' => now(),
            'updated_at' => now(),
            'visible' => false,
        ]);

        $system->assignRole(RoleEnum::getInstance(RoleEnum::System)->getModel());
        $system->save();

        $admin = new User([
            'username' => 'Admin',
            'firstname' => 'Admin',
            'surname' => 'Superadmin',
            'email' => config('app.admin_email'),
            'email_optional' => null,
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $admin->assignRole(RoleEnum::getInstance(RoleEnum::SuperAdmin)->getModel());
        $admin->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::truncate();
    }
}
