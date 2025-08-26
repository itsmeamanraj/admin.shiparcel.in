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
        Schema::create('shipments_tracking', function (Blueprint $table) {
            $table->id();
            $table->string('awb_number')->index();
            $table->string('status_code')->nullable(); 
            $table->timestamp('status_date')->nullable();
            $table->json('tracking_data')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments_tracking');
    }
};
