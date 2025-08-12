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
            // Xpressbees fields
            $table->string('xpressbees_awb_no')->nullable();
            $table->string('xpressbees_api_status_code')->nullable();
            $table->string('xpressbees_api_status')->nullable();
            $table->string('xpressbees_token_number')->nullable();
            $table->string('xpressbees_is_parked')->nullable();
            $table->string('xpressbees_payment_link')->nullable();

            // Invoice number field
            $table->string('invoice_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shiparcel_orders', function (Blueprint $table) {
                    $table->dropColumn([
                        'xpressbees_awb_no',
                        'xpressbees_api_status_code',
                        'xpressbees_api_status',
                        'xpressbees_token_number',
                        'xpressbees_is_parked',
                        'xpressbees_payment_link',
                    ]);
                });
    }
};
