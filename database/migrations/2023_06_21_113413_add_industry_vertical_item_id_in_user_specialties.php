<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndustryVerticalItemIdInUserSpecialties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_specialties', function (Blueprint $table) {
            $table->dropForeign(['general_title_id']);
            $table->dropColumn('general_title_id');
            $table->unsignedBigInteger('industry_vertical_item_id')->after('user_id');
            $table->foreign('industry_vertical_item_id')->references('id')->on('industry_vertical_items')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_specialties', function (Blueprint $table) {
            $table->dropForeign(['industry_vertical_item_id']);
            $table->dropColumn('industry_vertical_item_id');
            $table->unsignedBigInteger('general_title_id')->after('user_id');
            $table->foreign('general_title_id')->references('id')->on('general_titles')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
