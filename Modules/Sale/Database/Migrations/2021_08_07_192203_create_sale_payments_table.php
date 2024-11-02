<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sale_id')->references('id')->on('sales')->cascadeOnDelete();
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_payments');
    }
}
