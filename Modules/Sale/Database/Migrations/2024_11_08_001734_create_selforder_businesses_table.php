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
        Schema::create('selforder_businesses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('selforder_type_id')->references('id')->on('selforder_types')->cascadeOnDelete();
            $table->foreignUuid('business_id')->references('id')->on('business')->cascadeOnDelete();
            $table->string('subject')->nullable();
            $table->string('captions')->nullable();
            $table->string('subject')->nullable();
            $table->boolean('need_customers')->default(true);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selforder_businesses');
    }
};
