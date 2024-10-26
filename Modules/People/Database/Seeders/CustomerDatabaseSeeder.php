<?php

namespace Modules\People\Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;
use Modules\People\Entities\Customer;

class CustomerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $businessData = Business::all();

      foreach ($businessData as $business){
        $dataCustomerDefault = [
            [
            'customer_first_name' => 'Pelanggan',
            'customer_last_name' => 'Pertama '.$business->prefix,
            'customer_name' => 'Pelanggan Pertama '.$business->prefix,
            'customer_email' => 'pelangganpertama@email.com',
            'dob' => '2000-07-21',
            'gender' => 'MALE',
            'customer_phone' => '+628151234567',
            'city' => 'Kota Pelanggan Pertama '.$business->prefix,
            'country' => 'ID',
            'address' => 'Alamat Pelanggan Pertama '.$business->prefix,
            'postal_code' => '43152',
            'business_id' => $business->id
           ],
           [
            'customer_first_name' => 'Pelanggan',
            'customer_last_name' => 'Kedua '.$business->prefix,
            'customer_name' => 'Pelanggan Kedua '.$business->prefix,
            'dob' => '2000-07-21',
            'gender' => 'MALE',
           'customer_email' => 'pelangganpertama@email.com '.$business->prefix,
           'customer_phone' => '628151234567',
           'city' => 'Kota Pelanggan Kedua '.$business->prefix,
           'country' => 'Indonesia',
           'address' => 'Alamat Pelanggan Kedua '.$business->prefix,
           'postal_code' => '43152',
           'business_id' => $business->id
          ]
        ];
        foreach($dataCustomerDefault as $dataCustomer){
            Customer::create($dataCustomer);
        }
      }
    }
}
