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
