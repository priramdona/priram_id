<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_return_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('purchase_return_id')->references('id')->on('purchase_returns');
            $table->foreignUuid('product_id')->references('id')->on('products');
            $table->string('product_name');
            $table->string('product_code');
            $table->integer('quantity');
            $table->decimal('price',14,2)->default(0);
            $table->decimal('unit_price',14,2)->default(0);
            $table->decimal('sub_total',14,2)->default(0);
            $table->decimal('product_discount_amount',14,2)->default(0);
            $table->string('product_discount_type')->default('fixed');
            $table->decimal('product_tax_amount',14,2)->default(0);
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
        Schema::dropIfExists('purchase_return_details');
    }
}
