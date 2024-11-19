<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('category_id')->references('id')->on('categories');
            $table->string('product_name');
            $table->string('product_code')->nullable();
            $table->string('product_barcode_symbology')->nullable();
            $table->integer('product_quantity');
            $table->decimal('product_cost',14,2)->nullable();
            $table->decimal('product_price',14,2)->nullable();
            $table->decimal('product_stock_alert',14,2)->nullable();
            $table->decimal('product_order_tax',14,2)->nullable();
            $table->string('product_unit')->nullable();
            $table->tinyInteger('product_tax_type')->nullable();
            $table->text('product_note')->nullable();
            $table->boolean('is_default')->nullable()->nullable()->default(false);
            $table->boolean('is_showlist')->nullable()->default(true);
            $table->boolean('is_action')->nullable()->default(false);
            $table->string('action')->nullable()->default(null);
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
        Schema::dropIfExists('products');
    }
}
