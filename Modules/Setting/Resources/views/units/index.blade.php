@extends('layouts.app')

@section('title', __('unit.units'))

@section('third_party_stylesheets')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('unit.home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('unit.units') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <a href="{{ route('units.create') }}" class="btn btn-primary">
                            {{ __('unit.add_unit') }} <i class="bi bi-plus"></i>
                        </a>

                        <hr>

                        <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                            <table class="table table-bordered" style="table-layout: auto; width: 100%;" id="data-table">
                                <thead>
                                <tr>
                                    <th class="align-middle">{{ __('unit.no') }}</th>
                                    <th class="align-middle">{{ __('unit.name') }}</th>
                                    <th class="align-middle">{{ __('unit.short_name') }}</th>
                                    <th class="align-middle">{{ __('unit.operator') }}</th>
                                    <th class="align-middle">{{ __('unit.operation_value') }}</th>
                                    <th class="align-middle">{{ __('unit.action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($units as $key => $unit)
                                    <tr>
                                        <td class="align-middle">{{ $key + 1 }}</td>
                                        <td class="align-middle">{{ $unit->name }}</td>
                                        <td class="align-middle">{{ $unit->short_name }}</td>
                                        <td class="align-middle">{{ $unit->operator }}</td>
                                        <td class="align-middle">{{ $unit->operation_value }}</td>
                                        <td class="align-middle">
                                            <a href="{{ route('units.edit', $unit) }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                data-id="{{ $unit->id }}"
                                                data-name="{{ $unit->name }}"
                                                title="{{ __('user.delete_user') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel">{{ __('user.delete_confirmation') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p id="deleteMessage">{{ __('user.confirm_delete', ['name' => '']) }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('user.cancel') }}</button>
                                            <button type="button" class="btn btn-danger" id="modalConfirmDeleteBtn">{{ __('products.action_confirm_delete') }}</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-html5-1.7.0/b-print-1.7.0/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
    <script>

    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Tombol yang memicu modal
        var userId = button.getAttribute('data-id'); // Ambil ID dari atribut data-id
        var userName = button.getAttribute('data-name'); // Ambil nama dari atribut data-name
        $('#modalConfirmDeleteBtn').on('click', function() {
            var deleteUrl = "{{ route('units.destroy', ':id') }}";
            deleteUrl = deleteUrl.replace(':id', userId);
            var form = $('<form action="' + deleteUrl + '" method="POST">' +
                '@csrf' +
                '@method("DELETE")' +
                '</form>');
            $('body').append(form);
            form.submit();
        });
    });

        var table = $('#data-table').DataTable({
            dom: "<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4 justify-content-end'f>>tr<'row'<'col-md-5'i><'col-md-7 mt-2'p>>",
            "buttons": [
                {extend: 'excel',text: '<i class="bi bi-file-earmark-excel-fill"></i> {{ __('unit.excel') }}'},
                {extend: 'csv',text: '<i class="bi bi-file-earmark-excel-fill"></i> {{ __('unit.csv') }}'},
                {extend: 'print',
                    text: '<i class="bi bi-printer-fill"></i> {{ __('unit.print') }}',
                    title: "{{ __('unit.units') }}",
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    },
                    customize: function (win) {
                        $(win.document.body).find('h1').css('font-size', '15pt');
                        $(win.document.body).find('h1').css('text-align', 'center');
                        $(win.document.body).find('h1').css('margin-bottom', '20px');
                        $(win.document.body).css('margin', '35px 25px');
                    }
                },
            ],
            ordering: false,
            responsive: true,
            autoWidth: true,
            scrollX: true,

            language: {
                lengthMenu: "{{ __('unit.lengthMenu') }}",
                zeroRecords: "{{ __('unit.zeroRecords') }}",
                info: "{{ __('unit.info') }}",
                infoEmpty: "{{ __('unit.infoEmpty') }}",
                infoFiltered: "{{ __('unit.infoFiltered') }}",
                search: "{{ __('unit.search') }}",
                paginate: {
                    first: "{{ __('unit.paginate.first') }}",
                    last: "{{ __('unit.paginate.last') }}",
                    next: "{{ __('unit.paginate.next') }}",
                    previous: "{{ __('unit.paginate.previous') }}"
                }
            }
        });
    </script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

@endpush


@push('page_css')
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    table, th, td {
        border: 1px solid black;
    }
    th, td {
        padding: 8px;
        text-align: left;
    }
    #preview-table td {
        white-space: nowrap;
    }

</style>
@endpush
