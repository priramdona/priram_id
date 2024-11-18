@extends('layouts.app')

@section('title', __('purchase_return.create_purchase_return'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('purchase_return.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchase-returns.index') }}">{{ __('purchase_return.purchase_returns') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchase-returns.show', $purchase_return) }}">{{ $purchase_return->reference }}</a></li>
        <li class="breadcrumb-item active">{{ __('purchase_return.create_purchase_return') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <form id="payment-form" action="{{ route('purchase-return-payments.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                    <div class="form-group">
                        <button class="btn btn-primary">{{ __('purchase_return.create_purchase_return') }} <i class="bi bi-check"></i></button>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="reference">{{ __('purchase_return.reference') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="reference" required readonly value="INV/{{ $purchase_return->reference }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="date">{{ __('purchase_return.date') }} <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="date" required value="{{ now()->format('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="due_amount">{{ __('purchase_return.due_amount') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="due_amount" required value="{{ format_currency($purchase_return->due_amount) }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="amount">{{ __('purchase_return.amount') }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input id="amount" type="text" class="form-control" name="amount" required value="{{ old('amount') }}">
                                            <div class="input-group-append">
                                                <button id="getTotalAmount" class="btn btn-primary" type="button">
                                                    <i class="bi bi-check-square"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="from-group">
                                        <div class="form-group">
                                            <label for="payment_method">{{ __('purchase_return.payment_method') }} <span class="text-danger">*</span></label>
                                            <select class="form-control" name="payment_method" id="payment_method" required>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="note">{{ __('purchase_return.note') }}</label>
                                <textarea class="form-control" rows="4" name="note">{{ old('note') }}</textarea>
                            </div>

                            <input type="hidden" value="{{ $purchase_return->id }}" name="purchase_return_id">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('page_scripts')
    <script src="{{ asset('js/jquery-mask-money.js') }}"></script>
    <script>
        $(document).ready(function () {
            $.ajax({
            url: "{{ url('/get-payment-method') }}/",
            method: "GET",
            data: {
                'source': 'purchase_return',
            },
            dataType: 'json',
            success: function(data) {
                if (data.length > 0) {
                    $("#payment_method").empty();
                    op = '<option value="" disabled="true" selected="true">{{  __('purchase_return.select ') }}</option>'
                    for (var i = 0; i < data.length; i++) {
                        op += '<option value="' + data[i].id + '">' + data[i]
                            .name + '</option>';
                    }
                    $("#payment_method").append(op);
                    $('#payment_method').attr('hidden', false);
                    $('#payment_method').attr('hidden', false);

                } else {
                    $("#payment_method").empty();
                }
            }
        });

            $('#amount').maskMoney({
                prefix:'{{ settings()->currency->symbol }}',
                thousands:'{{ settings()->currency->thousand_separator }}',
                decimal:'{{ settings()->currency->decimal_separator }}',
            });

            $('#getTotalAmount').click(function () {
                $('#amount').maskMoney('mask', {{ $purchase_return->due_amount }});
            });

            $('#payment-form').submit(function () {
                var amount = $('#amount').maskMoney('unmasked')[0];
                $('#amount').val(amount);
            });
        });
    </script>
@endpush

