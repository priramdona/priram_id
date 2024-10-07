<?php

namespace Modules\Currency\Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Currency\Entities\Currency;

class CurrencyDatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        foreach (Business::all() as $business) {
            Currency::create([
                'currency_name'      => 'RP Indonesia',
                'code'               => Str::upper('ID'),
                'symbol'             => 'Rp. ',
                'thousand_separator' => ',',
                'decimal_separator'  => '.',
                'exchange_rate'      => null,
                'business_id'      => $business->id,
            ]);
        }

    }
}
