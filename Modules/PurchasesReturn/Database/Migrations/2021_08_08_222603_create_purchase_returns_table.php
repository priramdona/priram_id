<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->string('reference');
            $table->foreignUuid('supplier_id')->nullable()->references('id')->on('suppliers')->nullOnDelete();;
            $table->string('supplier_name');

            $table->decimal('tax_percentage',14,2)->default(0);

            $table->decimal('tax_amount',14,2)->default(0);
            $table->decimal('discount_percentage',14,2)->default(0);
            $table->decimal('discount_amount',14,2)->default(0);
            $table->decimal('shipping_amount',14,2)->default(0);
            $table->decimal('total_amount',14,2)->default(0);
            $table->decimal('paid_amount',14,2)->default(0);
            $table->decimal('due_amount',14,2)->default(0);
            $table->decimal('product_tax_amount',14,2)->default(0);
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
        Schema::dropIfExists('purchase_returns');
    }
}
