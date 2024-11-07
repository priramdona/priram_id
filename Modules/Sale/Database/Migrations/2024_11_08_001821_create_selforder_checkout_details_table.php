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
        Schema::create('selforder_checkout_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('selforder_checkout_id')->references('id')->on('selforder_checkouts')->cascadeOnDelete();
            $table->foreignUuid('product_id')->references('id')->on('products');
            $table->string('product_name');
            $table->string('product_code');
            $table->integer('quantity');
            $table->decimal('price',14,2)->nullable();
            $table->decimal('unit_price',14,2)->nullable();
            $table->decimal('sub_total',14,2)->nullable();
            $table->decimal('product_discount_amount',14,2)->nullable();
            $table->string('product_discount_type')->default('fixed');
            $table->decimal('product_tax_amount',14,2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selforder_checkout_details');
    }
};
