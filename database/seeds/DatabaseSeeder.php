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
