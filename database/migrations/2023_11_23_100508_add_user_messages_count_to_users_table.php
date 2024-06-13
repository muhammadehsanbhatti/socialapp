<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserMessagesCountToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('total_archived_messages_tmp')->default(0)->after('new_connection_count');
            $table->integer('total_unarchived_messages_tmp')->default(0)->after('total_archived_messages_tmp');
            $table->integer('total_unread_messages_tmp')->default(0)->after('total_unarchived_messages_tmp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('total_archived_messages_tmp');
            $table->dropColumn('total_unarchived_messages_tmp');
            $table->dropColumn('total_unread_messages_tmp');
        });
    }
}