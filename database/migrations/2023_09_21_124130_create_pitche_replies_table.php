<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePitcheRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pitche_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('pitch_id');
            $table->foreign('pitch_id')->references('id')->on('pitches')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('pitch_reply_id')->nullable();
            $table->foreign('pitch_reply_id')->references('id')->on('pitche_replies')->onUpdate('cascade')->onDelete('cascade');
            $table->longText('reply_message')->nullable();
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
        Schema::dropIfExists('pitche_replies');
    }
}