<?php

namespace Modules\PurchasesReturn\DataTables;

use Modules\PurchasesReturn\Entities\PurchaseReturn;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PurchaseReturnsDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->addColumn('total_amount', function ($data) {
                return format_currency($data->total_amount);
            })
            ->addColumn('paid_amount', function ($data) {
                return format_currency($data->paid_amount);
            })
            ->addColumn('due_amount', function ($data) {
                return format_currency($data->due_amount);
            })
            ->addColumn('sisa_amount', function ($data) {
                return ($data->due_amount);
            })
            ->addColumn('status', function ($data) {
                return view('purchasesreturn::partials.status', compact('data'));
            })
            ->addColumn('payment_status', function ($data) {
                return view('purchasesreturn::partials.payment-status', compact('data'));
            })
            ->addColumn('action', function ($data) {
                return view('purchasesreturn::partials.actions', compact('data'));
            });
    }

    public function query(PurchaseReturn $model) {
        return $model->newQuery();
    }

    public function html() {
        return $this->builder()
            ->setTableId('purchase-returns-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                'tr' .
                                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(7)
            ->buttons(
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel-fill"></i> ' . __('purchase_return.datatable.purchase_return.buttons.excel')),
                Button::make('print')
                    ->text('<i class="bi bi-printer-fill"></i> ' . __('purchase_return.datatable.purchase_return.buttons.print')),
                Button::make('reset')
                    ->text('<i class="bi bi-x-circle"></i> ' . __('purchase_return.datatable.purchase_return.buttons.reset')),
                Button::make('reload')
                    ->text('<i class="bi bi-arrow-repeat"></i> ' . __('purchase_return.datatable.purchase_return.buttons.reload'))
            )->parameters([
                'responsive' => true,
                'autoWidth' => true,
                'scrollX' => true,
                'language' => __('purchase_return.datatable.purchase_return.tools'),
        ]);
    }

    protected function getColumns() {
        return [
            Column::make('reference')
            ->title(__('purchase_return.datatable.purchase_return.columns.reference'))
                ->className('text-center align-middle'),

            Column::make('supplier_name')
            ->title(__('purchase_return.datatable.purchase_return.columns.supplier'))
                ->className('text-center align-middle'),

            Column::computed('status')
            ->title(__('purchase_return.datatable.purchase_return.columns.status'))
                ->className('text-center align-middle'),

            Column::computed('total_amount')
            ->title(__('purchase_return.datatable.purchase_return.columns.total_amount'))
                ->className('text-center align-middle'),

            Column::computed('paid_amount')
            ->title(__('purchase_return.datatable.purchase_return.columns.paid_amount'))
                ->className('text-center align-middle'),

            Column::computed('due_amount')
            ->title(__('purchase_return.datatable.purchase_return.columns.due_amount'))
                ->className('text-center align-middle'),

            Column::computed('payment_status')
            ->title(__('purchase_return.datatable.purchase_return.columns.payment_status'))
                ->className('text-center align-middle'),

            // Column::computed('action')
            // ->title(__('purchase_return.datatable.purchase_return.columns.action'))
            //     ->exportable(false)
            //     ->printable(false)
            //     ->className('text-center align-middle'),

            Column::make('created_at')
                ->visible(false)
        ];
    }

    protected function filename(): string {
        return 'PurchaseReturns_' . date('YmdHis');
    }
}
