<?php

use App\Buisness\Enum\RoleEnum;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class _ExampleUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 100)->create()->each(function($u) {
            $u->assignRole(RoleEnum::getInstance(RoleEnum::TeamIntern)->getModel());
            $u->save;
        });
    }
}
