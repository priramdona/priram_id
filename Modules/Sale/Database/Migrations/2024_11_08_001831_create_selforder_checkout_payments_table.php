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
        Schema::create('selforder_checkout_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('selforder_checkout_id')->references('id')->on('selforder_checkouts')->cascadeOnDelete();
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
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selforder_checkout_payments');
    }
};
