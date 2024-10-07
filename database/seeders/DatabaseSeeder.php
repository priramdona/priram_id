<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Currency\Database\Seeders\CurrencyDatabaseSeeder;
use Modules\Expense\Database\Seeders\ExpenseCategoryDatabaseSeeder;
use Modules\People\Database\Seeders\CustomerDatabaseSeeder;
use Modules\People\Database\Seeders\SupplierDatabaseSeeder;
use Modules\Product\Database\Seeders\ProductDatabaseSeeder;
use Modules\Setting\Database\Seeders\SettingDatabaseSeeder;
use Modules\User\Database\Seeders\PermissionsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SuperUserSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(CurrencyDatabaseSeeder::class);
        $this->call(SettingDatabaseSeeder::class);
        $this->call(ProductDatabaseSeeder::class);
        $this->call(CustomerDatabaseSeeder::class);
        $this->call(SupplierDatabaseSeeder::class);
        $this->call(ExpenseCategoryDatabaseSeeder::class);
    }
}
