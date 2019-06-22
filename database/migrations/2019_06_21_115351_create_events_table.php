<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->required();
            $table->string('description')->nullable();
            $table->integer('score')->nullable();
            $table->integer('max_participant')->default(0);
            $table->string('meeting_place')->required();
            $table->timestamp('date_event_start')->nullable();
            $table->timestamp('date_event_end')->nullable();
            $table->timestamp('date_sign_up_start')->nullable();
            $table->timestamp('date_sign_up_end')->nullable();
            $table->timestamp('date_publication')->nullable();
            $table->unsignedBigInteger('participation_status_id');

            $table->timestamps();

            $table->foreign('participation_status_id')
                ->references('id')
                ->on('participation_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
