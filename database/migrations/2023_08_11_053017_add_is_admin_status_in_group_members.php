<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAdminStatusInGroupMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_members', function (Blueprint $table) {
            $table->enum('is_delete', ['True','False'])->default('False')->after('member_last_message_id');
            $table->enum('is_admin', ['True','False'])->default('False')->after('is_delete');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_members', function (Blueprint $table) {
            $table->dropColumn('is_delete');
            $table->dropColumn('is_admin');
        });
    }
}
