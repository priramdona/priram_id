@extends('layouts.app')

@section('title', __('products.products'))

@section('third_party_stylesheets')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('selforder.home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('selforder.selforders') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <a href="{{ route('products.create') }}" class="btn btn-primary">
                            {{ __('products.add_product') }} <i class="bi bi-plus"></i>
                        </a>
                        <hr> --}}
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
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('selforder.lists.action_cancel') }}</button>
                                        <button type="button" class="btn btn-success" id="modalShowBtn">{{ __('selforder.lists.action_process') }}</button>
                                        {{-- <button type="button" class="btn btn-primary" id="modalEditBtn">{{ __('products.action_edit') }}</button>
                                        <button type="button" class="btn btn-danger" id="modalDeleteBtn">{{ __('products.action_delete') }}</button>
                                        <button type="button" class="btn btn-danger" id="modalConfirmDeleteBtn">{{ __('products.action_confirm_delete') }}</button> --}}
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

            $('#' + tableId + ' tbody').on('click', 'tr', function () {
                var data = table.row(this).data();
                selectedProductId = data.id;

                // var paymentStatus = $(data.payment_status).text();
                var paymentStatus = $(data.payment_status).text().trim();
                if (paymentStatus == 'waiting' || paymentStatus == 'Waiting'){
                    Swal.fire({
                    title: 'Payment Waiting',
                    text: 'Cannot Process while Payment is Waiting...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    icon: 'error',
                    didOpen: () => {
                        $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                    },
                    });
                    return;
                }
                  // Assuming 'id' is the column name for product ID
                showActionModal();
            });

            function showActionModal() {
                $('#modalMessage').text("{{ __('selforder.lists.action_modal_label') }}");
                $('#modalShowBtn').show();
                // $('#modalEditBtn').show();
                // $('#modalDeleteBtn').show();
                // $('#modalConfirmDeleteBtn').hide();
                $('#actionModal').modal('show');
            }

            // Handle modal edit button click
            // $('#modalEditBtn').on('click', function() {
            //     if (selectedProductId) {
            //         var editUrl = "{{ route('products.edit', ':id') }}";
            //         editUrl = editUrl.replace(':id', selectedProductId);
            //         window.location.href = editUrl;
            //     } else {
            //         console.error('No product selected');
            //     }
            // });

            // Handle modal edit button click
            $('#modalShowBtn').on('click', function() {
                if (selectedProductId) {
                    var editUrl = "{{ route('selfordercheckout', ':id') }}";
                    editUrl = editUrl.replace(':id', selectedProductId);
                    window.location.href = editUrl;
                } else {
                    console.error('No product selected');
                }
            });
            // Handle modal delete button click
            // $('#modalDeleteBtn').on('click', function() {
            //     $('#modalMessage').text("{{ __('products.confirm_delete_message') }}");
            //     $('#modalShowBtn').hide();
            //     $('#modalEditBtn').hide();
            //     $('#modalDeleteBtn').hide();
            //     $('#modalConfirmDeleteBtn').show();
            // });

            // Handle modal confirm delete button click
            // $('#modalConfirmDeleteBtn').on('click', function() {
            //     if (selectedProductId) {
            //         var deleteUrl = "{{ route('products.destroy', ':id') }}";
            //         deleteUrl = deleteUrl.replace(':id', selectedProductId);

            //         var form = $('<form action="' + deleteUrl + '" method="POST">' +
            //             '@csrf' +
            //             '@method("DELETE")' +
            //             '</form>');
            //         $('body').append(form);
            //         form.submit();
            //     } else {
            //         console.error('No product selected');
            //     }
            // });
        });
    </script>
@endpush
@push('page_css')
<style>
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
