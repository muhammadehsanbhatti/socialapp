<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_titles', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->enum('status', ['Published', 'Draft'])->default('Published');
            $table->tinyInteger('title_status')->comment('1=Career Status Position, 2=Professional Role, 3=Educational Information, 4=Specialty Skills, 5=Industry Vertical, 6=User Report')->nullable();
            $table->string('title_slug', 50)->nullable();
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
        Schema::dropIfExists('general_titles');
    }
}
