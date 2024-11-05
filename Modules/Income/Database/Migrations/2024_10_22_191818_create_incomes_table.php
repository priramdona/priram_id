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
        Schema::create('incomes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('category_id')->references('id')->on('income_categories');
            $table->foreignUuid('customer_id')->references('id')->on('customers')->nullable();
            $table->date('date');
            $table->string('reference');
            $table->text('details')->nullable();
            $table->decimal('amount',14,2);
            $table->decimal('additional_amount',14,2);
            $table->decimal('paid_amount',14,2);
            $table->string('payment_status');
            $table->foreignUuid('business_id')->references('id')->on('businesses')->after('id');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
