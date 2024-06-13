<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePitchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pitches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->enum('connection_status', ['Everyone', 'MyConnection', 'Filter'])->nullable();
            $table->longText('connection_search')->nullable();
            $table->integer('shares_count')->default(0);
            $table->integer('bookmark_count')->default(0);
            $table->integer('replies_count')->default(0);
            $table->enum('status', ['Published', 'Draft'])->nullable();
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
        Schema::dropIfExists('pitches');
    }
}
