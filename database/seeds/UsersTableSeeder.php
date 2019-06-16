<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Buisness\Enum\RoleEnum;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User([
            'name' => 'Florian Pitz',
            'email' => 'florian.pitz@tsvhofolding.de',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $admin->assignRole(RoleEnum::superAdmin()->getModel());
        $admin->save();



    }
}
