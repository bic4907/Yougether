<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Enums\VideoStatus;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->unsignedInteger('current_host')->references('id')->on('users')->nullable()->onDelete('cascade');
            $table->string('current_videoId')->nullable();
            $table->enum('current_videoStatus', [VideoStatus::Queued, VideoStatus::Playing, VideoStatus::Played])->nullable();
            $table->integer('current_time')->nullable();
            $table->integer('current_duration')->nullable();
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
        Schema::dropIfExists('rooms');
    }
}
