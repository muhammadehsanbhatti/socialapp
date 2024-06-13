<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConnectPeopleNoteToConnectPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connect_people', function (Blueprint $table) {
            $table->longText('connect_people_note')->nullable()->after('connect_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('connect_people', function (Blueprint $table) {
            $table->dropColumn('connect_people_note');
        });
    }
}