<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleReturnPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_return_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sale_return_id')->references('id')->on('sale_returns')->cascadeOnDelete();
            $table->decimal('amount',14,2)->default(0);
            $table->date('date');
            $table->string('reference');
            $table->string('payment_method');
            $table->foreignUuid('payment_method_id')->nullable();
            $table->string('payment_method_name')->nullable();
            $table->foreignUuid('payment_channel_id')->nullable();
            $table->string('payment_channel_name')->nullable();
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
        Schema::dropIfExists('sale_return_payments');
    }
}
