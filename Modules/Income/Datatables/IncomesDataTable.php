<?php

namespace Modules\Income\DataTables;

use Illuminate\Support\Facades\Auth;
use Modules\Income\Entities\Income;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class IncomesDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->addColumn('amount', function ($data) {
                return format_currency($data->amount);
            })
            ->addColumn('action', function ($data) {
                return view('income::incomes.partials.actions', compact('data'));
            });
    }

    public function query(Income $model) {
        return $model->where('business_id',Auth::user()->business_id)->newQuery()->with('category')->with('incomePayments');
    }

    public function html() {
        return $this->builder()
            ->setTableId('incomes-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                'tr' .
                                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(6)
            ->buttons(
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel-fill"></i> ' . __('expense.excel')),
                Button::make('print')
                    ->text('<i class="bi bi-printer-fill"></i> ' . __('expense.print')),
                Button::make('reset')
                    ->text('<i class="bi bi-x-circle"></i> ' . __('expense.reset')),
                Button::make('reload')
                    ->text('<i class="bi bi-arrow-repeat"></i> ' . __('expense.reload'))
            )->parameters([
                'responsive' => true,
                'autoWidth' => true,
                'scrollX' => true,
                'language' => __('expense.datatable'),
            ]);
    }

    protected function getColumns() {
        return [
            Column::make('date')
                ->title(__('income.date'))
                ->className('text-center align-middle'),

            Column::make('reference')
                ->title(__('income.reference'))
                ->className('text-center align-middle'),

            Column::make('category.category_name')
                ->title(__('income.category'))
                ->className('text-center align-middle'),

            Column::computed('amount')
                ->title(__('income.amount'))
                ->className('text-center align-middle'),

            Column::make('details')
                ->title(__('income.details'))
                ->className('text-center align-middle'),

            Column::computed('action')
                ->title(__('income.action'))
                ->exportable(false)
                ->printable(false)
                ->className('text-center align-middle'),

            Column::make('created_at')
                ->visible(false)
        ];
    }

    protected function filename(): string {
        return 'Incomes_' . date('YmdHis');
    }
}
