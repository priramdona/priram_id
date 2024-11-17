<?php

namespace Modules\Sale\DataTables;

use Illuminate\Support\Facades\Auth;
use Modules\Sale\Entities\SalePayment;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SalePaymentsDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->addColumn('amount', function ($data) {
                return format_currency($data->amount);
            })
            ->addColumn('action', function ($data) {
                return view('sale::payments.partials.actions', compact('data'));
            });
    }

    public function query(SalePayment $model) {
        return $model->where('business_id',Auth::user()->business_id)->newQuery()->bySale()->with('sale');
    }

    public function html() {
        return $this->builder()
            ->setTableId('sale-payments-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                'tr' .
                                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(5)
            ->buttons(
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel-fill"></i> ' . __('sales.datatable.buttons.excel')),
                Button::make('print')
                    ->text('<i class="bi bi-printer-fill"></i> ' . __('sales.datatable.buttons.print')),
                Button::make('reset')
                    ->text('<i class="bi bi-x-circle"></i> ' . __('sales.datatable.buttons.reset')),
                Button::make('reload')
                    ->text('<i class="bi bi-arrow-repeat"></i> ' . __('sales.datatable.buttons.reload'))
            )->parameters([
                'responsive' => true,
                'autoWidth' => true,
            'scrollX' => true,
            // 'columnDefs' => [
            //     ['responsivePriority' => 1, 'targets' => 0],
            //     ['responsivePriority' => 2, 'targets' => -1],
            //     ['responsivePriority' => 3, 'targets' => 1],
            // ],
                'language' => __('sales.datatable.tools'),
        ]);
    }

    protected function getColumns() {
        return [
            Column::make('date')
                ->title(__('sales.datatable.columns.date'))
                ->className('align-middle text-center'),

            Column::make('reference')
                ->title(__('sales.datatable.columns.reference'))
                ->className('align-middle text-center'),

            Column::computed('amount')
                ->title(__('sales.datatable.columns.amount'))
                ->className('align-middle text-center'),

            Column::make('payment_method')
                ->title(__('sales.datatable.columns.payment_method'))
                ->className('align-middle text-center'),

            Column::computed('action')
                ->title(__('sales.datatable.columns.action'))
                ->exportable(false)
                ->printable(false)
                ->className('align-middle text-center'),

            Column::make('created_at')
                ->visible(false),
        ];
    }

    protected function filename(): string {
        return 'SalePayments_' . date('YmdHis');
    }
}
