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
        Schema::create('data_configs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ppn_type')->default('%');
            $table->decimal('ppn_value',14,4)->default(11);
            $table->string('app_fee_type')->default('amount');
            $table->decimal('app_fee_value',14,4)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_configs');
    }
};
