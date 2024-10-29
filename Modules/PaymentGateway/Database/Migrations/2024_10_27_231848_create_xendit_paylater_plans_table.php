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
        Schema::create('xendit_paylater_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('plan_id');
            $table->foreignUuid('customer_id')->references('id')->on('customers')->after('id');
            $table->string('cust_id');
            $table->string('channel_code');
            $table->string('currency');
            $table->decimal('amount', 14, 2)->default(0);
            $table->dateTime('created')->nullable();
            $table->json('order_items')->nullable();
            $table->json('options')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xendit_paylater_plans');
    }
};
