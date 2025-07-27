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
        Schema::create('shiparcel_orders', function (Blueprint $table) {
            $table->id();
            $table->string('client_order_id')->nullable();
            $table->string('consignee_emailid')->nullable();
            $table->string('consignee_pincode')->nullable();
            $table->string('consignee_mobile')->nullable();
            $table->string('consignee_phone')->nullable();
            $table->text('consignee_address1')->nullable();
            $table->text('consignee_address2')->nullable();
            $table->string('consignee_name')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('express_type')->default('surface');
            $table->unsignedBigInteger('pick_address_id')->nullable();
            $table->string('return_address_id')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('courier_type')->nullable();
            $table->decimal('cod_amount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('order_amount', 10, 2)->default(0);
            $table->string('shipment_width')->nullable();
            $table->string('shipment_height')->nullable();
            $table->string('shipment_length')->nullable();
            $table->string('shipment_weight')->nullable();
            $table->json('products')->nullable();
            $table->string('awb_number')->nullable();
            $table->string('order_number')->nullable();
            $table->string('job_id')->nullable();
            $table->string('lrnum')->nullable();
            $table->json('waybills_num_json')->nullable();
            $table->text('lable_data')->nullable();
            $table->string('routing_code')->nullable();
            $table->string('partner_display_name')->nullable();
            $table->string('courier_code')->nullable();
            $table->string('pickup_id')->nullable();
            $table->string('courier_name')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('product_sku')->nullable();
            $table->string('product_name')->nullable();
            $table->decimal('product_value', 10, 2);
            $table->string('product_hsnsac')->nullable();
            $table->decimal('product_taxper', 5, 2)->default(0);
            $table->string('product_category')->nullable();
            $table->integer('product_quantity')->nullable();
            $table->text('product_description')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('shiparcel_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('shiparcel_orders');
    }
};
