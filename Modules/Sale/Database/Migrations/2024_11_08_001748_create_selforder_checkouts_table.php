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
        Schema::create('selforder_checkouts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('selforder_business_id')->references('id')->on('selforder_businesses')->cascadeOnDelete();
            $table->foreignUuid('business_id')->references('id')->on('businesses')->cascadeOnDelete();
            $table->string('business_name')->nullable();
            $table->date('date');
            $table->string('reference');
            $table->foreignUuid('customer_id')->nullable()->references('id')->on('customers')->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->decimal('tax_percentage',14,2)->nullable();
            $table->decimal('tax_amount',14,2)->nullable();
            $table->decimal('discount_percentage',14,2)->nullable();
            $table->decimal('discount_amount',14,2)->nullable();
            $table->decimal('shipping_amount',14,2)->nullable();
            $table->decimal('total_amount',14,2)->nullable();
            $table->decimal('paid_amount',14,2)->nullable();
            $table->decimal('additional_paid_amount',14,2)->nullable();
            $table->decimal('total_paid_amount',14,2)->nullable();
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
     */
    public function down(): void
    {
        Schema::dropIfExists('selforder_checkouts');
    }
};
