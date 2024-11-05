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
        Schema::create('message_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->boolean('is_read')->default(false);
            $table->nullableUuidMorphs('source');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_notifications');
    }
};
