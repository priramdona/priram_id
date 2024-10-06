<?php

namespace Modules\Setting\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Setting\Entities\Setting;

class SettingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'company_name' => 'Prima Raharja Mandiri',
            'company_email' => 'company@priram.id',
            'company_phone' => '012345678901',
            'notification_email' => 'notification@test.com',
            'default_currency_id' => 1,
            'default_currency_position' => 'prefix',
            'footer_text' => 'Kasir Priram © 2024 || Developed by <strong><a target="_blank" href="www.priram.id">Priram</a></strong>',
            'company_address' => 'Ciapu'
        ]);
    }
}
