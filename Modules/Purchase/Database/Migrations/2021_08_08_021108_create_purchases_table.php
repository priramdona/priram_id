<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->string('reference');
            $table->foreignUuid('supplier_id')->nullable()->references('id')->on('suppliers');
            $table->string('supplier_name');

            $table->decimal('tax_percentage',14,2)->nullable();
            $table->decimal('tax_amount',14,2)->nullable();
            $table->decimal('discount_percentage',14,2)->nullable();
            $table->decimal('discount_amount',14,2)->nullable();
            $table->decimal('shipping_amount',14,2)->nullable();
            $table->decimal('total_amount',14,2)->nullable();
            $table->decimal('paid_amount',14,2)->nullable();
            $table->decimal('due_amount',14,2)->nullable();
            $table->string('status');
            $table->string('payment_status');
            $table->string('payment_method');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('purchases');
    }
}
