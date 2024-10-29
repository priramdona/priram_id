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
           'province' => 'Jawa Barat',
           'city' => 'Kota Pelanggan Pertama '.$business->prefix,
            'country' => 'ID',
            'address' => 'Alamat Pelanggan Pertama '.$business->prefix,
            'postal_code' => '43152',
            'business_id' => $business->id,
            'cust_id' => 'cust-cdb01b74-ec2b-4be2-8f4e-2f7d3cc4353c'
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
           'province' => 'Jawa Barat',
           'country' => 'Indonesia',
           'address' => 'Alamat Pelanggan Kedua '.$business->prefix,
           'postal_code' => '43152',
           'business_id' => $business->id,
            'cust_id' => 'cust-cdb01b74-ec2b-4be2-8f4e-2f7d3cc4353c'
          ]
        ];
        foreach($dataCustomerDefault as $dataCustomer){
            Customer::create($dataCustomer);
        }
      }
    }
}
