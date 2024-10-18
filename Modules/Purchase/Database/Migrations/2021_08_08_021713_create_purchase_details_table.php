<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('purchase_id')->references('id')->on('purchases')->cascadeOnDelete();;
            $table->foreignUuid('product_id')->references('id')->on('products');
            $table->string('product_name');
            $table->string('product_code');
            $table->integer('quantity');
            $table->integer('price');
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_details');
    }
}
