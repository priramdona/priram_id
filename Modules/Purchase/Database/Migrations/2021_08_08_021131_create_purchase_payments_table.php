<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('purchase_id')->references('id')->on('purchases')->cascadeOnDelete();
            $table->foreignUuid('payment_method_id')->nullable();
            $table->string('payment_method');
            $table->string('payment_method_name')->nullable();
            $table->foreignUuid('payment_channel_id')->nullable();
            $table->string('payment_channel_name')->nullable();
            $table->foreignUuid('xendit_create_payment_id')->nullable();
            $table->decimal('amount',14,2)->nullable();
            $table->date('date');
            $table->string('reference');
            $table->string('reference_id')->nullable();
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
        Schema::dropIfExists('purchase_payments');
    }
}
