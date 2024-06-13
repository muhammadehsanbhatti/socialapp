<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->foreign('sender_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->foreign('receiver_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('groups')->onUpdate('cascade')->onDelete('cascade');
            $table->longText('message')->nullable();
            $table->enum('type', ['Single', 'Broadcast', 'Group'])->nullable();
            $table->enum('message_status', ['Block'])->nullable();
            $table->enum('message_delete', ['For Me', 'For Every One'])->nullable();
            $table->unsignedBigInteger('message_reply_id')->nullable();
            $table->foreign('message_reply_id')->references('id')->on('messages')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('message_asset_reply_id')->nullable();
            // $table->foreign('message_asset_reply_id')->references('id')->on('message_assets')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('is_forwarded', ['True','False'])->default('False');
            $table->softDeletes();
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
        Schema::dropIfExists('messages');
    }
}
