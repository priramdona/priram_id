<?php

namespace Modules\User\DataTables;

use Spatie\Permission\Models\Role;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

use Illuminate\Support\Facades\Auth;
class RolesDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($data) {
                return view('user::roles.partials.actions', compact('data'));
            })
            ->addColumn('permissions', function ($data) {
                return view('user::roles.partials.permissions', [
                    'data' => $data
                ]);
            });

    }

    public function query(Role $model) {
        return $model->newQuery()->with(['permissions' => function ($query) {
            $query->select('name')->take(10)->get();
        }])->where('name', '!=', 'Super Admin')->where('business_id', Auth::user()->business_id);
    }

    public function html() {
        return $this->builder()
            ->setTableId('roles-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                'tr' .
                                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(4)
            ->buttons(
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel-fill"></i> ' . __('user.excel')),
                Button::make('print')
                    ->text('<i class="bi bi-printer-fill"></i> ' . __('user.print')),
                Button::make('reset')
                    ->text('<i class="bi bi-x-circle"></i> ' . __('user.reset')),
                Button::make('reload')
                    ->text('<i class="bi bi-arrow-repeat"></i> ' . __('user.reload'))
            )->parameters([
                'responsive' => true,
                'autoWidth' => true,
                'scrollX' => true,
                'language' => __('user.datatable'),
            ]);
    }

    protected function getColumns() {
        return [
            Column::make('id')
                ->addClass('text-center')
                ->addClass('align-middle'),

            Column::make('name')
            ->title(__('user.name'))
                ->addClass('text-center')
                ->addClass('align-middle'),

            Column::computed('permissions')
            ->title(__('user.permissions'))
                ->addClass('text-center')
                ->addClass('align-middle')
                ->width('700px'),

            Column::computed('action')
            ->title(__('user.action'))
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->addClass('align-middle'),

            Column::make('created_at')
                ->visible(false)
        ];
    }

    protected function filename(): string {
        return 'Roles_' . date('YmdHis');
    }
}
