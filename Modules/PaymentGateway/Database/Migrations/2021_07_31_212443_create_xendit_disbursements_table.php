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
        Schema::create('xendit_disbursements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('disbursement_id')->nullable();
            $table->string('external_id')->nullable();
            $table->decimal('amount',14,4)->nullable();
            $table->string('bank_code')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('disbursement_description')->nullable();
            $table->boolean('is_instant')->default(true);
            $table->boolean('status')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xendit_disbursements');
    }
};
