<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProRoleTypeItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_role_type_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pro_role_type_id');
            $table->foreign('pro_role_type_id')->references('id')->on('prof_role_types')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('pro_role_type_items');
    }
}
