@extends('layouts.app')

@section('title', __('sales.index.title'))

@section('third_party_stylesheets')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('sales.breadcrumb.home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('sales.breadcrumb.sales') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('sales.create') }}" class="btn btn-primary">
                            {{ __('sales.index.add_sale') }} <i class="bi bi-plus"></i>
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
                                        <button type="button" class="btn btn-warning" id="modalInvoiceBtn">
                                            <i class="bi bi-file-earmark-pdf mr-2" style="line-height: 1;"></i>
                                            {{ __('sales.actions.pos_invoice') }}</button>
                                        @can('access_sale_payments')
                                        <button type="button" class="btn btn-warning" id="modalPaymentBtn">
                                            <i class="bi bi-cash-coin mr-2" style="line-height: 1;"></i>
                                                {{ __('sales.actions.show_payments') }}</button>
                                        @endcan
                                        @can('access_sale_payments')
                                        <button type="button" class="btn btn-warning" id="modalAddPaymentBtn">
                                            <i class="bi bi-plus-circle-dotted mr-2" style="line-height: 1;"></i>
                                                {{ __('sales.actions.add_payment') }}</button>
                                        </div>
                                        @endcan
                                        <br>

                                        @can('show_sales')
                                        <button type="button" class="btn btn-success" id="modalShowBtn">
                                            <i class="bi bi-eye mr-2" style="line-height: 1;"></i> {{ __('sales.actions.details') }}</button>
                                        @endcan
                                        @can('edit_sales')
                                        <button type="button" class="btn btn-primary" id="modalEditBtn">
                                            <i class="bi bi-pencil mr-2" style="line-height: 1;"></i> {{ __('sales.actions.edit') }}</button>
                                        @endcan
                                        @can('delete_sales')
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
                var dueAmount = parseFloat(
                    data.due_amount.replace(/[^\d.-]/g, '') // Hapus semua kecuali angka, tanda desimal, dan tanda minus
                );

                $.ajax({
                url: "{{ url('/get-payment-sales') }}/",
                method: "GET",
                data: {
                    'sale_id': data.id,
                },
                dataType: 'json',
                success: function(data) {
                    isOnline = data.result;
                }
            });

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
                    $('#modalInvoiceBtn').show();
                    $('#modalPaymentBtn').show();
                    if (dueAmount > 0) {
                        $('#modalAddPaymentBtn').show();
                    }else{
                        $('#modalAddPaymentBtn').hide();
                    }

                    $('#actionModal').modal('show');
                } else {
                    console.error('No product selected');
                }
            }

             // Handle modal edit button click
             $('#modalEditBtn').on('click', function() {
                if (selectedProductId) {
                    var editUrl = "{{ route('sales.edit', ':id') }}";
                    editUrl = editUrl.replace(':id', selectedProductId);
                    window.location.href = editUrl;
                } else {
                    console.error('No product selected');
                }
            });

            // Handle modal Invoice button click
            $('#modalInvoiceBtn').on('click', function() {
                if (selectedProductId) {
                    var invoiceUrl = "{{ route('sales.pos.pdf', ':id') }}";
                    invoiceUrl = invoiceUrl.replace(':id', selectedProductId);
                    window.location.href = invoiceUrl;
                } else {
                    console.error('No product selected');
                }
            });

            // Handle modal Pembayaran button click
            $('#modalPaymentBtn').on('click', function() {
                if (selectedProductId) {
                    var paymentUrl = "{{ route('sale-payments.index', ':id') }}";
                    paymentUrl = paymentUrl.replace(':id', selectedProductId);
                    window.location.href = paymentUrl;
                } else {
                    console.error('No product selected');
                }
            });

            // Handle modal AddPembayaran button click
            $('#modalAddPaymentBtn').on('click', function() {
                if (selectedProductId) {
                    var addPaymentUrl = "{{ route('sale-payments.create', ':id') }}";
                    addPaymentUrl = addPaymentUrl.replace(':id', selectedProductId);
                    window.location.href = addPaymentUrl;
                } else {
                    console.error('No product selected');
                }
            });

            // Handle modal edit button click
            $('#modalShowBtn').on('click', function() {
                if (selectedProductId) {
                    var editUrl = "{{ route('sales.show', ':id') }}";
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
                $('#modalInvoiceBtn').hide();
                $('#modalPaymentBtn').hide();
                $('#modalAddPaymentBtn').hide();
                $('#modalConfirmDeleteBtn').show();
            });

            // Handle modal confirm delete button click
            $('#modalConfirmDeleteBtn').on('click', function() {
                if (selectedProductId) {
                    var deleteUrl = "{{ route('sales.destroy', ':id') }}";
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
    #product-table tbody tr {
        cursor: pointer;
    }
</style>
@endpush
