<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('video');
            $table->boolean('isPlayed');
            $table->unsignedInteger('room_id');
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
