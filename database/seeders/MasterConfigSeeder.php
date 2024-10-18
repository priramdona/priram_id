<?php

namespace Database\Seeders;

use App\Models\DataConfig;
use App\Models\masterConfig;
use App\Models\MasterConfig as ModelsMasterConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DataConfig::create([
            'ppn_type' => '%',
            'ppn_value' => '11',
            'app_fee_type' => 'amount',
            'app_fee_type' => 0,

        ]);
    }
}
