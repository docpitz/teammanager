<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Buisness\Enum\ParticipationStatusEnum;
use App\ParticipationStatus;

class CreateParticipationStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participation_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
        });
        foreach (ParticipationStatusEnum::getInstances() as $status)
        {
            ParticipationStatus::firstOrCreate(["name" => $status->key]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participation_statuses');
    }
}
