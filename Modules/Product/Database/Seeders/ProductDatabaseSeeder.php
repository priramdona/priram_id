<?php

namespace Modules\Product\Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Product;
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
            $categoryMaxId = Category::where('business_id',$business->id)->count('id') + 1;
            $categoryCode = $business->prefix . str_pad($categoryMaxId, 4, '0', STR_PAD_LEFT);

            $category = Category::create([
                'category_code' => $categoryCode,
                'category_name' => 'Obat',
                'business_id' => $business->id,
            ]);

            Unit::create([
                'name' => 'Satuan',
                'short_name' => 'Satuan',
                'operator' => '*',
                'operation_value' => 1,
                'business_id' => $business->id,
                'is_default' => true,
            ]);

            Product::create([
                'category_id' => $category->id,
                'product_name' => 'Paramex',
                'product_code' => str_pad(mt_rand(1,999999999999),12,'0',STR_PAD_LEFT),
                'product_barcode_symbology' => 'EAN13',
                'product_quantity' => '1000',
                'product_cost' => '10500',
                'product_price' => '12000',
                'product_unit' => 'Satuan',
                'product_stock_alert' => '1',
                'product_note' => 'Data Seeder',
                'business_id' => $business->id,
            ]);

            // $defaultPulsa = Category::create([
            //     'category_code' => $business->prefix . str_pad($categoryMaxId + 1, 4, '0', STR_PAD_LEFT),
            //     'category_name' => 'Pulsa',
            //     'business_id' => $business->id,
            //     'is_showlist' => false,
            //     'is_default' => true,
            // ]);

            // Product::create([
            //     'category_id' => $defaultPulsa->id,
            //     'product_name' => 'Simpati 10Rb',
            //     'product_code' => str_pad(mt_rand(1,999999999999),12,'0',STR_PAD_LEFT),
            //     'product_barcode_symbology' => 'EAN13',
            //     'product_quantity' => '99999',
            //     'product_cost' => '10500',
            //     'product_price' => '12000',
            //     'product_unit' => 'Satuan',
            //     'product_stock_alert' => '1',
            //     'product_note' => 'Data Seeder',
            //     'business_id' => $business->id,
            //     'is_showlist' => false,
            //     'is_default' => true,
            //     'is_action' => true,
            //     'action' => 'phone_number',
            // ]);
        }

    }

}
