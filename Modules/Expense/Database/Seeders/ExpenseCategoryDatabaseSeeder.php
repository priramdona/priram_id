<?php

namespace Modules\Expense\Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;
use Modules\Expense\Entities\ExpenseCategory;

class ExpenseCategoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $businessData = Business::all();
        foreach ($businessData as $business) {
            $category = ExpenseCategory::create([
                'category_name' => 'Transport',
                'category_description' => 'Transport Acomodations fee / Akomodasi biaya transportasi',
                'business_id' => $business->id,
            ]);
        }

    }
}
