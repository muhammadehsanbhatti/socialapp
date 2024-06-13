<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePitchSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pitch_shares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('pitch_id');
            $table->foreign('pitch_id')->references('id')->on('pitches')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('share_to_user')->nullable();
            $table->foreign('share_to_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('share_to_group')->nullable();
            $table->foreign('share_to_group')->references('id')->on('groups')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('share_to_social', ['Social' ,'ConnectedUser'])->default('Social');
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
        Schema::dropIfExists('pitch_shares');
    }
}
