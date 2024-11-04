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
        Schema::create('xendit_invoice_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_id')->references('id')->on('customers')->after('id');
            $table->string('xen_invoice_id');
            $table->string('external_id');
            $table->string('user_id');
            $table->string('payer_email');
            $table->string('description');
            $table->json('payment_method');
            $table->string('status');
            $table->string('merchant_name');
            $table->string('merchant_profile_picture_url');
            $table->string('locale');
            $table->decimal('amount',14,2);
            $table->dateTime('expiry_date')->nullable();
            $table->text('invoice_url');
            $table->json('available_banks')->nullable();
            $table->json('available_retail_outlets')->nullable();
            $table->json('available_ewallets')->nullable();
            $table->json('available_qr_codes')->nullable();
            $table->json('available_direct_debits')->nullable();
            $table->json('available_paylaters')->nullable();
            $table->boolean('should_send_email');
            $table->boolean('should_exclude_credit_card');
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->string('success_redirect_url');
            $table->string('failure_redirect_url');
            $table->boolean('should_authenticate_credit_card');
            $table->string('currency');
            $table->json('items')->nullable();
            $table->boolean('fixed_va')->default(false);
            $table->dateTime('reminder_date')->nullable();
            $table->json('customer')->nullable();
            $table->json('customer_notification_preference')->nullable();
            $table->json('fees')->nullable();
            $table->json('channel_properties')->nullable();
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
        Schema::dropIfExists('xendit_invoice_requests');
    }
};
