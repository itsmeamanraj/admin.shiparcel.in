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
        Schema::create('user_surface_courier_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('courier_company_id');
            $table->unsignedBigInteger('courier_weight_slab_id');
            $table->enum('mode', ['Forward', 'RTO']); // Mode of transport
            $table->string('zone'); // A, B, C, D, E
            $table->decimal('forward_fwd', 8, 2)->nullable();
            $table->decimal('additional_fwd', 8, 2)->nullable();
            $table->decimal('forward_rto', 8, 2)->nullable();
            $table->decimal('additional_rto', 8, 2)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('courier_company_id')->references('id')->on('courier_companies')->onDelete('cascade');
            // $table->foreign('courier_weight_slab_id')->references('id')->on('courier_weight_slabs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_surface_courier_rates');
    }
};
