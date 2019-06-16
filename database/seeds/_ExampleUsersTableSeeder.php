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
        $organisator = new User([
            'name' => 'Daniela Pitz',
            'email' => 'daniela.pitz@tsvhofolding.de',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $organisator->assignRole(RoleEnum::getInstance(RoleEnum::Organisator)->getModel());
        $organisator->save();

        $teamMemberOne = new User([
            'name' => 'Magdalena Pitz',
            'email' => 'lena.pitz@gute-loesung.de',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $teamMemberOne->assignRole(RoleEnum::getInstance(RoleEnum::TeamIntern)->getModel());
        $teamMemberOne->save();

        $teamMemberTwo = new User([
            'name' => 'Lennart Rieger',
            'email' => 'lennart.rieger@gmx.de',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $teamMemberTwo->assignRole(RoleEnum::getInstance(RoleEnum::TeamIntern)->getModel());
        $teamMemberTwo->save();
    }
}
