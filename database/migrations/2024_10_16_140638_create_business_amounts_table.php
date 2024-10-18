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
            $table->decimal('amount',14,4)->nullable();
            $table->decimal('sale_amount',14,4)->nullable();
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
