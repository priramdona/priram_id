<?php

namespace Modules\PurchasesReturn\DataTables;

use Modules\PurchasesReturn\Entities\PurchaseReturnPayment;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PurchaseReturnPaymentsDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->addColumn('amount', function ($data) {
                return format_currency($data->amount);
            })
            ->addColumn('action', function ($data) {
                return view('purchasesreturn::payments.partials.actions', compact('data'));
            });
    }

    public function query(PurchaseReturnPayment $model) {
        return $model->newQuery()->byPurchaseReturn()->with('purchaseReturn');
    }

    public function html() {
        return $this->builder()
            ->setTableId('purchase-payments-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                'tr' .
                                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(5)
            ->buttons(
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel-fill"></i> ' . __('purchase_return.datatable.payment.buttons.excel')),
                Button::make('print')
                    ->text('<i class="bi bi-printer-fill"></i> ' . __('purchase_return.datatable.payment.buttons.print')),
                Button::make('reset')
                    ->text('<i class="bi bi-x-circle"></i> ' . __('purchase_return.datatable.payment.buttons.reset')),
                Button::make('reload')
                    ->text('<i class="bi bi-arrow-repeat"></i> ' . __('purchase_return.datatable.payment.buttons.reload'))
            )->parameters([
                'responsive' => true,
                'autoWidth' => true,
                'scrollX' => true,
                'language' => __('purchase_return.datatable.payment.tools'),
        ]);
    }

    protected function getColumns() {
        return [
            Column::make('date')
            ->title(__('purchase_return.datatable.payment.columns.date'))
                ->className('align-middle text-center'),

            Column::make('reference')
            ->title(__('purchase_return.datatable.payment.columns.reference'))
                ->className('align-middle text-center'),

            Column::computed('amount')
            ->title(__('purchase_return.datatable.payment.columns.amount'))
                ->className('align-middle text-center'),

            Column::make('payment_method')
            ->title(__('purchase_return.datatable.payment.columns.payment_method'))
                ->className('align-middle text-center'),

            Column::computed('action')
            ->title(__('purchase_return.datatable.payment.columns.action'))
                ->exportable(false)
                ->printable(false)
                ->className('align-middle text-center'),

            Column::make('created_at')
                ->visible(false),
        ];
    }

    protected function filename(): string {
        return 'PurchaseReturnPayments_' . date('YmdHis');
    }
}
