<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryStatusInIndustryVerticalItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('industry_vertical_items', function (Blueprint $table) {
            $table->enum('category_status', ['Industry', 'Specialty', 'Both'])->default('Industry')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('industry_vertical_items', function (Blueprint $table) {
            $table->dropColumn('category_status');
        });
    }
}
