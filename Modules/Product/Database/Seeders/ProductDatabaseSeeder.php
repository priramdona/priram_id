<?php

namespace Modules\Product\Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Category;
use Modules\Setting\Entities\Unit;

class ProductDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        foreach (Business::get() as $business){
            Category::create([
                'category_code' => 'CA_01' . $business->prefix,
                'category_name' => 'Random',
                'business_id' => $business->id,
            ]);

            Unit::create([
                'name' => 'Piece',
                'short_name' => 'PC',
                'operator' => '*',
                'operation_value' => 1,
                'business_id' => $business->id,
            ]);
        }

    }
}
