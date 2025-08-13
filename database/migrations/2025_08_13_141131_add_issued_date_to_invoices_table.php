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
        Schema::table('wallet_transactions', function (Blueprint $table) {
            // user_role column add
            $table->string('user_role')->nullable()->after('status');

            // sirf nullable banana
            $table->string('invoice_number')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropColumn('user_role');

            // wapas NOT NULL banana
            $table->string('invoice_number')->nullable(false)->change();
        });
    }
};
