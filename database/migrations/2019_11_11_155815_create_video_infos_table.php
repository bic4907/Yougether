<?php

use App\Enums\VideoStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('videoId');
            $table->string('videoTitle');
            $table->text('videoDesc')->nullable();
            $table->enum('videoStatus',[VideoStatus::Queued, VideoStatus::Playing, VideoStatus::Played]);
            $table->integer('duration');
            $table->text('tags')->nullable();
            $table->text('thumbnail')->nullable();
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
        Schema::dropIfExists('video_infos');
    }
}
