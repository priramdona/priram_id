<?php

namespace Modules\People\DataTables;

use Illuminate\Support\Facades\Auth;
use Modules\People\Entities\Customer;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CustomersDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($data) {
                return view('people::customers.partials.actions', compact('data'));
            });
    }

    public function query(Customer $model) {
        return $model->where('business_id',Auth::user()->business_id)->newQuery();
    }

    public function html() {
        return $this->builder()
            ->setTableId('customers-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                       'tr' .
                                 <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(4)
            ->buttons(
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel-fill"></i> ' . __('people.excel')),
                Button::make('print')
                    ->text('<i class="bi bi-printer-fill"></i> ' . __('people.print')),
                Button::make('reset')
                    ->text('<i class="bi bi-x-circle"></i> ' . __('people.reset')),
                Button::make('reload')
                    ->text('<i class="bi bi-arrow-repeat"></i> ' . __('people.reload'))
            )->parameters([
                'responsive' => true,
                'autoWidth' => true,
                'scrollX' => true,
                'language' => __('people.datatable'),
            ]);
    }

    protected function getColumns() {
        return [
            Column::make('customer_name')
                ->className('text-center align-middle')
                ->title(__('people.customer_name')),

            Column::make('customer_email')
                ->className('text-center align-middle')
                ->title(__('people.email')),

            Column::make('customer_phone')
                ->className('text-center align-middle')
                ->title(__('people.phone')),

            Column::computed('action')
                ->title(__('people.action'))
                ->exportable(false)
                ->printable(false)
                ->className('text-center align-middle'),

            Column::make('created_at')
                ->visible(false)
        ];
    }

    protected function filename(): string {
        return 'Customers_' . date('YmdHis');
    }
}
