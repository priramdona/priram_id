@extends('layouts.app')

@section('title', __ ('quotation.breadcrumb.quotation'))

@section('third_party_stylesheets')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('quotation.breadcrumb.home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('quotation.breadcrumb.quotations') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('quotations.create') }}" class="btn btn-primary">
                            {{ __('quotation.datatable.add') }} <i class="bi bi-plus"></i>
                        </a>

                        <hr>

                        <div class="table-responsive">
                            {!! $dataTable->table([
                                'class' => 'table table-hover nowrap',
                            ], true) !!}
                        </div>
                         <!-- Action Modal -->
                         <div class="modal fade" id="actionModal" tabindex="-1" role="dialog" aria-labelledby="actionModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="actionModalLabel">{{ __('products.action_modal') }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p id="modalMessage">{{ __('products.action_modal_label') }}</p>
                                        <div>
                                        @can('create_quotation_sales')
                                        <button type="button" class="btn btn-warning" id="modalMakeSaleBtn">
                                            <i class="bi bi-check2-circle mr-2" style="line-height: 1;"></i>
                                            {{ __('sales.actions.make_sale') }}</button>
                                        @endcan
                                        @can('send_quotation_mails')
                                        <button type="button" class="btn btn-warning" id="sendEmailBtn">
                                            <i class="bi bi-cursor mr-2" style="line-height: 1;"></i>
                                            {{ __('sales.actions.send_email') }}</button>
                                        @endcan
                                        </div>
                                        <br>

                                        @can('show_quotations')
                                        <button type="button" class="btn btn-success" id="modalShowBtn">
                                            <i class="bi bi-eye mr-2" style="line-height: 1;"></i> {{ __('sales.actions.details') }}</button>
                                        @endcan
                                        @can('edit_quotations')
                                        <button type="button" class="btn btn-primary" id="modalEditBtn">
                                            <i class="bi bi-pencil mr-2" style="line-height: 1;"></i> {{ __('sales.actions.edit') }}</button>
                                        @endcan
                                        @can('delete_quotations')
                                        <button type="button" class="btn btn-danger" id="modalDeleteBtn">
                                            <i class="bi bi-trash mr-2" style="line-height: 1;"></i> {{ __('sales.actions.delete') }}</button>
                                        @endcan
                                        <button type="button" class="btn btn-danger" id="modalConfirmDeleteBtn">
                                            <i class="bi bi-check mr-2" style="line-height: 1;"></i> {{ __('products.action_confirm_delete') }}</button>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('products.action_cancel') }}</button>

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
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function() {
            var tableId = '{!! $dataTable->getTableId() !!}';
            var table = $('#' + tableId).DataTable();
            var selectedProductId;
            var dueAmount;
            var isOnline;

            $('#' + tableId + ' tbody').on('click', 'tr', function () {
                var data = table.row(this).data();
                selectedProductId = data.id;
                isOnline = data.with_invoice;
                showActionModal();
            });

            function showActionModal() {
                if (selectedProductId) {
                    $('#modalMessage').text("{{ __('sales.action_modal_label') }}");
                    $('#modalShowBtn').show();
                    if(isOnline == "0"){
                        $('#modalEditBtn').show();
                        $('#modalDeleteBtn').show();
                    }else{
                        $('#modalEditBtn').hide();
                        $('#modalDeleteBtn').hide();
                    }
                    $('#modalConfirmDeleteBtn').hide();
                    $('#modalMakeSaleBtn').show();
                    $('#sendEmailBtn').show();
                    $('#actionModal').modal('show');
                } else {
                    console.error('No product selected');
                }
            }

             // Handle modal edit button click
             $('#modalEditBtn').on('click', function() {
                if (selectedProductId) {
                    var editUrl = "{{ route('quotations.edit', ':id') }}";
                    editUrl = editUrl.replace(':id', selectedProductId);
                    window.location.href = editUrl;
                } else {
                    console.error('No product selected');
                }
            });

            // Handle Send Email button click
            $('#sendEmailBtn').on('click', function() {
                if (selectedProductId) {
                    var invoiceUrl = "{{ route('quotation.email', ':id') }}";
                    invoiceUrl = invoiceUrl.replace(':id', selectedProductId);
                    window.location.href = invoiceUrl;
                } else {
                    console.error('No product selected');
                }
            });


            // Handle modal Invoice button click
            $('#modalMakeSaleBtn').on('click', function() {
                if (selectedProductId) {
                    var invoiceUrl = "{{ route('quotation-sales.create', ':id') }}";
                    invoiceUrl = invoiceUrl.replace(':id', selectedProductId);
                    window.location.href = invoiceUrl;
                } else {
                    console.error('No product selected');
                }
            });

            // Handle modal edit button click
            $('#modalShowBtn').on('click', function() {
                if (selectedProductId) {
                    var editUrl = "{{ route('quotations.show', ':id') }}";
                    editUrl = editUrl.replace(':id', selectedProductId);
                    window.location.href = editUrl;
                } else {
                    console.error('No product selected');
                }
            });
            // Handle modal delete button click
            $('#modalDeleteBtn').on('click', function() {
                $('#modalMessage').text("{{ __('sales.actions.delete_confirm') }}");
                $('#modalShowBtn').hide();
                $('#modalEditBtn').hide();
                $('#modalDeleteBtn').hide();
                $('#modalMakeSaleBtn').hide();
                $('#sendEmailBtn').hide();
                $('#modalPaymentBtn').hide();
                $('#modalConfirmDeleteBtn').show();
            });

            // Handle modal confirm delete button click
            $('#modalConfirmDeleteBtn').on('click', function() {
                if (selectedProductId) {
                    var deleteUrl = "{{ route('quotations.destroy', ':id') }}";
                    deleteUrl = deleteUrl.replace(':id', selectedProductId);

                    var form = $('<form action="' + deleteUrl + '" method="POST">' +
                        '@csrf' +
                        '@method("DELETE")' +
                        '</form>');
                    $('body').append(form);
                    form.submit();
                } else {
                    console.error('No product selected');
                }
            });
        });
    </script>
@endpush

@push('page_css')
<style>
         table.dataTable tbody tr {
            height: 60px; /* Atur tinggi baris */
        }

        table.dataTable tbody td {
            padding: 15px;
            vertical-align: middle; /* Konten di tengah */
        }

    @media screen and (max-width: 767px) {
        div.dataTables_wrapper div.dataTables_length,
        div.dataTables_wrapper div.dataTables_filter,
        div.dataTables_wrapper div.dataTables_info,
        div.dataTables_wrapper div.dataTables_paginate {
            text-align: left;
            margin-top: 5px;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .dataTables_wrapper .row {
            margin: 0;
        }
    }
    #quotations-table tbody tr {
        cursor: pointer;
    }
</style>
@endpush
