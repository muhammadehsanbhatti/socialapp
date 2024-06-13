<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeedStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seed_stores', function (Blueprint $table) {
            $table->id();
            $table->enum('package_name', ['Small Pack', 'Medium Pack', 'Large Pack']);
            $table->integer('price');
            $table->integer('seeds_count');
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
        Schema::dropIfExists('seed_stores');
    }
}
