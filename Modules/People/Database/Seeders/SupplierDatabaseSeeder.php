<?php

namespace Modules\People\Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;
use Modules\People\Entities\Supplier;

class SupplierDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $businessData = Business::all();

      foreach ($businessData as $business){
        $dataSupplierDefault = [
            [
            'supplier_name' => 'Supplier Supplier '.$business->prefix,
            'supplier_email' => 'supplierpertama@email.com',
            'supplier_phone' => '628151234567',
            'city' => 'Kota Supplier Pertama '.$business->prefix,
            'country' => 'Indonesia',
            'address' => 'Alamat Supplier Pertama '.$business->prefix,
            'business_id' => $business->id
           ],
           [
            'supplier_name' => 'Supplier Kedua '.$business->prefix,
           'supplier_email' => 'supplierpertama@email.com '.$business->prefix,
           'supplier_phone' => '628151234567',
           'city' => 'Kota Supplier Kedua '.$business->prefix,
           'country' => 'Indonesia',
           'address' => 'Alamat Supplier Kedua '.$business->prefix,
           'business_id' => $business->id
          ]
        ];
        foreach($dataSupplierDefault as $dataSupplier){
            Supplier::create($dataSupplier);
        }
      }
    }
}
