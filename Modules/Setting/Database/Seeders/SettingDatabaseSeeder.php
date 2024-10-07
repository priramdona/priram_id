<?php

namespace Modules\Setting\Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Currency\Entities\Currency;
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
      $business = Business::all();

        foreach ($business as $businessData) {
            $curencyData = Currency::where('business_id', $businessData->id)->first();

            Setting::create([
                'company_name' => $businessData->name,
                'company_email' => 'company@priram.id',
                'company_phone' => $businessData->phone,
                'notification_email' => 'notification@test.com',
                'default_currency_id' => $curencyData->id,
                'default_currency_position' => 'prefix',
                'footer_text' => 'Kasir Priram © 2024 || Developed by <strong><a target="_blank" href="www.priram.id">Priram</a></strong>',
                'company_address' => 'Ciapu',
                'business_id' => $businessData->id,
            ]);
        }
    }
}
