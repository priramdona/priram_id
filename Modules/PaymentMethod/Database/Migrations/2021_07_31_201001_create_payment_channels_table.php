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
        Schema::create('payment_channels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('payment_method_id')->references('id')->on('payment_methods')->after('id');
            $table->string('name');
            $table->string('code');
            $table->string('type');
            $table->integer('min');
            $table->integer('max');
            $table->boolean('status');
            $table->text('image');
            $table->string('action')->default('deeplink');
            $table->string('reference')->nullable();
            $table->string('fee_type_1')->nullable();
            $table->decimal('fee_value_1',14,4)->nullable();
            $table->string('fee_type_2')->nullable();
            $table->decimal('fee_value_2',14,4)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_channels');
    }
};
