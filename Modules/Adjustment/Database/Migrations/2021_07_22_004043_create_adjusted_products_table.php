<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdjustedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjusted_products', function (Blueprint $table) {
            $table->uuid('id')->primary();;
            $table->foreignUuid('adjustment_id')->references('id')->on('adjustments')->onDelete('cascade');
            $table->foreignUuid('product_id')->references('id')->on('products');
            $table->integer('quantity');
            $table->string('type');
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
        Schema::dropIfExists('adjusted_products');
    }
}
