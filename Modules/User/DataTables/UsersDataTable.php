<?php

namespace Modules\User\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

use Illuminate\Support\Facades\Auth;
class UsersDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->addColumn('role', function ($data) {
                return view('user::users.partials.roles', [
                    'roles' => $data->getRoleNames()
                ]);
            })
            ->addColumn('action', function ($data) {
                return view('user::users.partials.actions', compact('data'));
            })
            ->addColumn('status', function ($data) {
                if ($data->is_active == 1) {
                    $html = '<span class="badge badge-success">Active</span>';
                } else {
                    $html = '<span class="badge badge-warning">Deactivated</span>';
                }

                return $html;
            })
            ->addColumn('image', function ($data) {
                // $url = $data->getFirstMediaUrl('avatars');
                $url = $data->image_url;

                return '<img src="' . $url . '" style="width:50px;height:50px;" class="img-thumbnail rounded-circle"/>';
            })
            ->rawColumns(['image', 'status']);
    }

    public function query(User $model) {
        return $model->newQuery()
            ->with(['roles' => function ($query) {
                $query->select('name')->get();
            }])
            ->where('id', '!=', Auth::user()->id)
            ->where('business_id', Auth::user()->business_id);
    }

    public function html() {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                'tr' .
                                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(6)
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
            Column::computed('image')
            ->title(__('user.image'))
                ->className('text-center align-middle'),

            Column::make('name')
            ->title(__('user.name'))
                ->className('text-center align-middle'),

            Column::make('email')
            ->title(__('user.email'))
                ->className('text-center align-middle'),

            Column::computed('role')
            ->title(__('user.role'))
                ->className('text-center align-middle'),

            Column::computed('status')
            ->title(__('user.status'))
                ->className('text-center align-middle'),

            Column::computed('action')
            ->title(__('user.action'))
                ->exportable(false)
                ->printable(false)
                ->className('text-center align-middle'),

            Column::make('created_at')
                ->visible(false)
        ];
    }

    protected function filename(): string {
        return 'Users_' . date('YmdHis');
    }
}
