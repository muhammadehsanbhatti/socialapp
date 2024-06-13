<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('type', ['Normal', 'Archived'])->default('Normal');
            $table->enum('member_status', ['Block'])->nullable();
            $table->unsignedBigInteger('member_last_message_id')->nullable();
            $table->foreign('member_last_message_id')->references('id')->on('messages')->onUpdate('cascade')->onDelete('cascade');
            // $table->enum('is_delete', ['True','False'])->default('False');
            // $table->enum('is_admin', ['True','False'])->default('False');
            
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
        Schema::dropIfExists('group_members');
    }
}
