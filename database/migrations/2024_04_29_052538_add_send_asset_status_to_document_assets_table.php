<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSendAssetStatusToDocumentAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_assets', function (Blueprint $table) {
            $table->enum('pitch_asset_status', ['S3_bucket','Server'])->default('Server')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_assets', function (Blueprint $table) {
            $table->dropForeign(['pitch_asset_status']);
        });
    }
}
