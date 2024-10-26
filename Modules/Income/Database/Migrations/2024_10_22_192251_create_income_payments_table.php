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
        Schema::create('income_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('income_id')->references('id')->on('incomes')->cascadeOnDelete();
            $table->string('payment_method')->nullable();
            $table->foreignUuid('payment_method_id')->nullable();
            $table->foreignUuid('xendit_create_payment_id')->nullable();
            $table->string('payment_method_name')->nullable();
            $table->foreignUuid('payment_channel_id')->nullable();
            $table->string('payment_channel_name')->nullable();
            $table->decimal('amount',14,2)->nullable();
            $table->date('date');
            $table->string('reference');
            $table->string('reference_id')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('income_payments');
    }
};
