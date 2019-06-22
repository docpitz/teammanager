<?php

use App\Buisness\Enum\ParticipationStatusEnum;
use App\ParticipationStatus;
use Illuminate\Database\Seeder;

class ParticipationStatusesTableSeeder extends Seeder
{
    const NAME = 'name';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (ParticipationStatusEnum::getInstances() as $status)
        {
            ParticipationStatus::create([self::NAME => $status->key]);
        }
    }
}
