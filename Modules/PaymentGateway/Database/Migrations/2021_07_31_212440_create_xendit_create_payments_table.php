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
            $table->nullableUuidMorphs('source');
            $table->nullableUuidMorphs('transactional');
            $table->decimal('amount', 14, 2)->default(0);
            $table->decimal('transaction_amount', 14, 2)->default(0);
            $table->string('payment_type');
            $table->string('channel_code');
            $table->string('status');
            $table->json('paid_information')->nullable();
            $table->dateTime('transaction_timestamp')->nullable();
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
