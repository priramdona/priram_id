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
        Schema::table('xendit_disbursements', function (Blueprint $table) {
            $table->foreignUuid('business_id')->references('id')->on('businesses')->after('id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('xendit_disbursements', function (Blueprint $table) {
            $table->dropColumn('business_id');
        });
    }
};
