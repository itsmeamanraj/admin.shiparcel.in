<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('shiparcel_orders', function (Blueprint $table) {
        $table->string('ekart_tracking_id')->nullable()->change();
        $table->string('ekart_shipment_payment_link')->nullable()->change();
        $table->string('ekart_is_parked')->nullable()->change();
        $table->string('ekart_request_id')->nullable()->change();
        $table->string('ekart_api_status_code')->nullable()->change();
        $table->string('ekart_api_status')->nullable()->change();
    });
}

public function down()
{
    Schema::table('shiparcel_orders', function (Blueprint $table) {
        $table->string('ekart_tracking_id')->nullable(false)->change();
        $table->string('ekart_shipment_payment_link')->nullable(false)->change();
        $table->string('ekart_is_parked')->nullable(false)->change();
        $table->string('ekart_request_id')->nullable(false)->change();
        $table->string('ekart_api_status_code')->nullable(false)->change();
        $table->string('ekart_api_status')->nullable(false)->change();
    });
}

};
