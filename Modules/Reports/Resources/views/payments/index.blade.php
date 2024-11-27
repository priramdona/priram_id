@extends('layouts.app')

@section('title', __('report.payment_report'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('menu.home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('report.payment_report') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <livewire:reports.payments-report/>
    </div>
@endsection
{{--
@push('page_scripts')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-html5-1.7.0/b-print-1.7.0/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<script>

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
@endpush --}}

