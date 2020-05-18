<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUserAvatarToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->string('avatar')->nullable()->change()->default(null)->change();

        });
        DB::update("UPDATE users SET avatar = NULL where avatar = 'default.png'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::update("UPDATE users SET avatar = 'default.png' where avatar IS NULL");
        Schema::table('users', function($table)
        {
            $table->string('avatar')->default("default.png")->change();
        });
    }
}
