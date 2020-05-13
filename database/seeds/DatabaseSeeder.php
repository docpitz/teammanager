<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('roles')->truncate();
        DB::table('users')->truncate();

        $this->call([RolesAndPermissionTableSeeder::class,
            UsersTableSeeder::class,
            ParticipationStatusesTableSeeder::class,
            ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        if($this->isTestSystem())
        {
            // Only Test
            $this->call([_ExampleUsersTableSeeder::class]);
        }
    }

    private function isTestSystem() : bool
    {
        return App::environment('local');
    }
}
