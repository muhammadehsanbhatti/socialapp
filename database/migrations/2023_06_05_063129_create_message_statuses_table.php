<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('block_user_id_from');
            $table->foreign('block_user_id_from')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('block_user_id_to');
            $table->foreign('block_user_id_to')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('general_title_id')->nullable();
            $table->foreign('general_title_id')->references('id')->on('general_titles')->onUpdate('cascade')->onDelete('cascade');
            $table->text('report_message')->nullable();
            $table->enum('type', ['Block', 'Archived', 'Report','UnArchived','Unblock'])->nullable();
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
        Schema::dropIfExists('message_statuses');
    }
}