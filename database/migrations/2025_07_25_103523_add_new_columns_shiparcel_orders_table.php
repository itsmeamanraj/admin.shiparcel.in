<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shiparcel_orders', function (Blueprint $table) {
            $table->string('ekart_tracking_id');
            $table->string('ekart_shipment_payment_link');
            $table->string('ekart_is_parked');
            $table->string('ekart_request_id');
            $table->string('ekart_api_status_code');
            $table->string('ekart_api_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shiparcel_orders', function (Blueprint $table) {
            $table->dropColumn('ekart_tracking_id');
            $table->dropColumn('ekart_api_status');
            $table->dropColumn('ekart_api_status_code');
            $table->dropColumn('ekart_request_id');
            $table->dropColumn('ekart_is_parked');
            $table->dropColumn('ekart_shipment_payment_link');
        });
    }
};
