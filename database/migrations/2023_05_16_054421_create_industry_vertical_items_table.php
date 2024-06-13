<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndustryVerticalItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('industry_vertical_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('general_title_id');
            $table->foreign('general_title_id')->references('id')->on('general_titles')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('industry_vertical_items');
    }
}
