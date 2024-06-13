<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRefuseConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_refuse_connections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id_from');
            $table->foreign('user_id_from')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('user_id_to')->nullable();
            $table->foreign('user_id_to')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('status', ['Pass'])->default('Pass');
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
        Schema::dropIfExists('user_refuse_connections');
    }
}
