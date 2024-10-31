<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('company_name');
            $table->string('company_email');
            $table->string('company_phone');
            $table->string('site_logo')->nullable();
            // $table->integer('default_currency_id');
            $table->foreignUuid('default_currency_id')->references('id')->on('currencies');

            $table->string('locale')->default('id');
            $table->string('default_currency_position');
            $table->string('notification_email');
            $table->text('footer_text');
            $table->text('company_address');
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
        Schema::dropIfExists('settings');
    }
}
