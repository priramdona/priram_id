<?php

namespace Modules\Expense\Http\Controllers;

use Modules\Expense\DataTables\ExpenseCategoriesDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Expense\Entities\ExpenseCategory;

use Illuminate\Validation\Rule;
class ExpenseCategoriesController extends Controller
{

    public function index(ExpenseCategoriesDataTable $dataTable) {
        abort_if(Gate::denies('access_expense_categories'), 403);

        return $dataTable->render('expense::categories.index');
    }

    public function store(Request $request) {
        abort_if(Gate::denies('access_expense_categories'), 403);

        $request->validate([
            'category_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('expense_categories', 'category_name')
                    ->where('business_id', $request->user()->business_id)
                    ->whereNull('deleted_at')
            ],
            'category_description' => 'nullable|string|max:1000'
        ]);

        ExpenseCategory::create([
            'category_name' => $request->category_name,
            'category_description' => $request->category_description,
            'business_id' => $request->user()->business_id,
        ]);

        toast(__('controller.created'), 'success');

        return redirect()->route('expense-categories.index');
    }


    public function edit(ExpenseCategory $expenseCategory) {
        abort_if(Gate::denies('access_expense_categories'), 403);

        return view('expense::categories.edit', compact('expenseCategory'));
    }


    public function update(Request $request, ExpenseCategory $expenseCategory) {
        abort_if(Gate::denies('access_expense_categories'), 403);

        $request->validate([
            'category_name' => [
            'required',
            'string',
            'max:255',
        Rule::unique('expense_categories', 'category_name')
            ->ignore($expenseCategory->id)
            ->where('business_id', $request->user()->business_id)
            ->whereNull('deleted_at')
            ],
            'category_description' => 'nullable|string|max:1000'
        ]);

        $expenseCategory->update([
            'category_name' => $request->category_name,
            'category_description' => $request->category_description
        ]);

        toast(__('controller.updated'), 'info');

        return redirect()->route('expense-categories.index');
    }


    public function destroy(ExpenseCategory $expenseCategory) {
        abort_if(Gate::denies('access_expense_categories'), 403);

        if ($expenseCategory->expenses->isNotEmpty()) {
            return back()->withErrors(__('controller.category_exist'));
        }

        $expenseCategory->delete();

        toast(__('controller.deleted'), 'warning');

        return redirect()->route('expense-categories.index');
    }
}
