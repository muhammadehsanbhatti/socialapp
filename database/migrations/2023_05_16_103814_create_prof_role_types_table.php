<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfRoleTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prof_role_types', function (Blueprint $table) {
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
        Schema::dropIfExists('prof_role_types');
    }
}
