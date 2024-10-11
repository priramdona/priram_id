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
        Schema::create('xendit_create_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reference_id');
            $table->decimal('amount', 14, 2)->default(0);
            $table->decimal('min_amount', 14, 2)->default(0);
            $table->decimal('max_amount', 14, 2)->default(0);
            $table->string('country')->default('ID');
            $table->string('currency')->default('IDR');
            $table->string('customer')->nullable();
            $table->string('checkout_method')->default('ONE_TIME_PAYMENT');
            $table->string('channel_code')->default('ONE_TIME_PAYMENT');
            $table->json('channel_properties')->nullable();
            $table->string('payment_method_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->json('basket')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xendit_create_payments');
    }
};
