<?php

use Illuminate\Database\Seeder;

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
        DB::table('tags')->truncate();
        DB::table('item_tag')->truncate();
        DB::table('categories')->truncate();
        DB::table('items')->truncate();

        $this->call([RolesAndPermissionTableSeeder::class,
            UsersTableSeeder::class,
            TagsTableSeeder::class,
            CategoriesTableSeeder::class,
            ItemsTableSeeder::class,
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
        return strcmp(config('APP_ENV'),'local');
    }
}
