<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_assets', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('user_id');
            // $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('pitch_id')->nullable();
            $table->foreign('pitch_id')->references('id')->on('pitches')->onUpdate('cascade')->onDelete('cascade');
            $table->string('path')->nullable();
            $table->string('name')->nullable();
            $table->string('size')->nullable();
            $table->string('extension')->nullable();
            $table->enum('status', ['Pitch Asset'])->nullable();
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
        Schema::dropIfExists('document_assets');
    }
}
