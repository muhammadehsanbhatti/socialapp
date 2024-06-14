<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavouriteVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favourite_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('upload_video_id')->nullable();
            $table->foreign('upload_video_id')->references('id')->on('upload_videos')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('fav_count')->nullable();
            $table->integer('share_count')->nullable();
            $table->integer('download_count')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('favourite_videos');
    }
}
