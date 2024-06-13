<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goal_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('goal_id');
            $table->foreign('goal_id')->references('id')->on('goals')->onUpdate('cascade')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->enum('status', ['Published', 'Draft'])->default('Published');
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
        Schema::dropIfExists('goal_items');
    }
}
