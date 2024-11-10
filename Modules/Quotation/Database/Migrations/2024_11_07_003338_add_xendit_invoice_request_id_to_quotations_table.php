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
        Schema::table('quotations', function (Blueprint $table) {

            $table->foreignUuid('xendit_invoice_request_id')->nullable();
            // $table->foreignUuid('xendit_invoice_request_id')->references('id')->on('xendit_invoice_requests')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('xendit_invoice_request_id');
        });
    }
};
