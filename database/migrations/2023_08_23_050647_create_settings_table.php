<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('profile_visibility', ['Everyone', 'Privacy Filter', 'None'])->default('Everyone');
            $table->longText('privacy_filter')->nullable();
            $table->enum('connections', ['Everyone', 'Manual'])->default('Everyone');
            $table->longText('connection_search')->nullable();
            // $table->tinyInteger('pitch_notification')->comment('1=Check, 2=Uncheck')->default(1);
            $table->enum('pitch_notification', ['Check', 'Uncheck'])->nullable();
            $table->enum('pitch_sound', ['Sound', 'None', 'Vibration'])->default('Sound');
            $table->enum('pitch_flash_notification', ['Yes', 'No'])->default('Yes');

            // $table->tinyInteger('message_notification')->comment('1=Check, 2=Uncheck')->default(1);
            $table->enum('message_notification', ['Check', 'Uncheck'])->nullable();
            $table->enum('message_sound', ['Sound', 'None', 'Vibration'])->default('Sound');
            $table->enum('message_flash_notification', ['Yes', 'No'])->default('Yes');



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
        Schema::dropIfExists('settings');
    }
}
