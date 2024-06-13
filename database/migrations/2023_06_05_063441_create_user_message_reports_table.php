<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMessageReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_message_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_status_id');
            $table->foreign('message_status_id')->references('id')->on('message_statuses')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('general_title_id')->nullable();
            $table->foreign('general_title_id')->references('id')->on('general_titles')->onUpdate('cascade')->onDelete('cascade');
            $table->text('report_message')->nullable();
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
        Schema::dropIfExists('user_message_reports');
    }
}