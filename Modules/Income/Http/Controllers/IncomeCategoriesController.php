<?php

namespace Modules\Income\Http\Controllers;

use Modules\Income\Datatables\IncomeCategoriesDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Income\Entities\IncomeCategory;
use Illuminate\Validation\Rule;

class IncomeCategoriesController extends Controller
{

    public function index(IncomeCategoriesDataTable $dataTable) {
        abort_if(Gate::denies('access_income_categories'), 403);

        return $dataTable->render('income::categories.index');
    }

    public function store(Request $request) {
        abort_if(Gate::denies('access_income_categories'), 403);

        $request->validate([
            'category_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('income_categories', 'category_name')
                ->where('business_id', $request->user()->business_id)
                ->whereNull('deleted_at')
            ],
            'category_description' => 'nullable|string|max:1000'
        ]);

        IncomeCategory::create([
            'category_name' => $request->category_name,
            'category_description' => $request->category_description,
            'business_id' => $request->user()->business_id,
        ]);

        toast(__('controller.created'), 'success');

        return redirect()->route('income-categories.index');
    }


    public function edit(IncomeCategory $incomeCategory) {
        abort_if(Gate::denies('access_income_categories'), 403);

        return view('income::categories.edit', compact('incomeCategory'));
    }


    public function update(Request $request, IncomeCategory $incomeCategory) {
        abort_if(Gate::denies('access_income_categories'), 403);

        $request->validate([
            'category_name' => [
            'required',
            'string',
            'max:255',
        Rule::unique('income_categories', 'category_name')
            ->ignore($incomeCategory->id)
            ->where('business_id', $request->user()->business_id)
            ->whereNull('deleted_at')
            ],
            'category_description' => 'nullable|string|max:1000'
        ]);

        $incomeCategory->update([
            'category_name' => $request->category_name,
            'category_description' => $request->category_description
        ]);

        toast(__('controller.updated'), 'info');

        return redirect()->route('income-categories.index');
    }


    public function destroy(incomeCategory $incomeCategory) {
        abort_if(Gate::denies('access_income_categories'), 403);

        if ($incomeCategory->incomes->isNotEmpty()) {
            return back()->withErrors('Can\'t delete beacuse there are incomes associated with this category.');
        }

        $incomeCategory->delete();

        toast(__('controller.deleted'), 'warning');

        return redirect()->route('income-categories.index');
    }
}
