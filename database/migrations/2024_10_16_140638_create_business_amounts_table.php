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
        Schema::create('business_amounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('business_id')->references('id')->on('businesses')->after('id');
            $table->tinyInteger('status_credit')->default(1)->nullable();
            $table->nullableUuidMorphs('transactional');
            $table->string('reference_id');
            $table->decimal('amount',14,4)->nullable();
            $table->decimal('sale_amount',14,4)->nullable();
            $table->decimal('received_amount',14,4)->nullable();
            $table->decimal('deduction_amount',14,4)->nullable();
            $table->decimal('calculated_amount', 14)->virtualAs('(amount * status_credit)')->nullable();
            $table->decimal('calculated_sale_amount', 14)->virtualAs('(sale_amount * status_credit)')->nullable();
            $table->decimal('calculated_received_amount', 14)->virtualAs('(received_amount * status_credit)')->nullable();
            $table->decimal('calculated_deduction_amount', 14)->virtualAs('(deduction_amount * status_credit)')->nullable();
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
        Schema::dropIfExists('business_amounts');
    }
};
