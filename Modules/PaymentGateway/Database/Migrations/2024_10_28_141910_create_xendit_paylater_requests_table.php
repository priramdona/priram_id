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
        Schema::create('xendit_paylater_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('xen_business_id');
            $table->string('reference_id');
            $table->foreignUuid('customer_id')->references('id')->on('customers')->after('id');
            $table->string('cust_id');
            $table->foreignUuid('xendit_paylater_plan_id')->references('id')->on('xendit_paylater_plans')->after('id');
            $table->string('plan_id');
            $table->string('currency');
            $table->decimal('amount', 14, 2)->default(0);
            $table->string('channel_code');
            $table->string('checkout_method');
            $table->string('status');
            $table->json('actions')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->string('success_redirect_url');
            $table->string('failure_redirect_url');
            $table->string('callback_url');
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->json('order_items')->nullable();
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
        Schema::dropIfExists('xendit_paylater_requests');
    }
};
