<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->string('reference');
            $table->foreignUuid('customer_id')->references('id')->on('customers');
            $table->string('customer_name');
            $table->decimal('tax_percentage',14,2)->default(0);
            $table->decimal('tax_amount',14,2)->default(0);
            $table->decimal('discount_percentage',14,2)->default(0);
            $table->decimal('discount_amount',14,2)->default(0);
            $table->decimal('shipping_amount',14,2)->default(0);
            $table->decimal('total_amount',14,2)->default(0);
            $table->string('status');
            $table->boolean('with_invoice')->default(false);
            $table->string('invoice_status')->nullable();
            $table->text('invoice_url')->nullable();
            $table->date('invoice_expiry_date')->nullable();
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
        Schema::dropIfExists('quotations');
    }
}
