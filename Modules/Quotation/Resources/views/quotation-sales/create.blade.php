@extends('layouts.app')

@section('title', __('quotation.sale.title'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('quotation.breadcrumb.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}">{{ __('quotation.breadcrumb.quotations') }}</a></li>
        <li class="breadcrumb-item active">{{ __('quotation.sale.make_sale') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-12">
                <livewire:search-product/>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @include('utils.alerts')
                        <form id="sale-form" action="{{ route('sales.store') }}" method="POST">
                            @csrf

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="reference">{{ __('quotation.form.reference') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="reference" required readonly value="SL">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="from-group">
                                        <div class="form-group">
                                            <label for="customer_id">{{ __('quotation.form.customer') }} <span class="text-danger">*</span></label>
                                            <select class="form-control" name="customer_id" id="customer_id" {{ $sale->with_invoice ? 'readonly' : 'required' }}>
                                                @foreach(\Modules\People\Entities\Customer::where('business_id', Auth::user()->business_id)->get() as $customer)
                                                    <option {{ $sale->customer_id == $customer->id ? 'selected' : '' }} value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="from-group">
                                        <div class="form-group">
                                            <label for="date">{{ __('quotation.form.date') }} <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="date" required value="{{ now()->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <livewire:product-cart :cartInstance="'sale'" :data="$sale"/>

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="status">{{ __('quotation.form.status') }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="status" id="status" required>
                                            <option value="Pending">{{ __('quotation.sale.status_options.pending') }}</option>
                                            <option value="Shipped">{{ __('quotation.sale.status_options.shipped') }}</option>
                                            <option value="Completed">{{ __('quotation.sale.status_options.completed') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="from-group">
                                        <div class="form-group">
                                            @php
                                                $invoiceInfo = \Modules\PaymentMethod\Entities\PaymentMethod::where('code','INVOICE')->first();
                                            @endphp
                                            <label for="payment_method">{{ __('quotation.sale.payment_method') }} <span class="text-danger">*</span></label>
                                            <select class="form-control" name="payment_method" id="payment_method"  {{ $sale->with_invoice ? 'readonly' : 'required' }}>
                                                <option {{ $sale->with_invoice ? 'selected' : '' }} value="{{ $invoiceInfo->id ?? '' }}">{{ $invoiceInfo->name ?? '' }}</option>

                                                <option value="Cash">{{ __('quotation.sale.payment_options.cash') }}</option>
                                                <option value="Credit Card">{{ __('quotation.sale.payment_options.credit_card') }}</option>
                                                <option value="Bank Transfer">{{ __('quotation.sale.payment_options.bank_transfer') }}</option>
                                                <option value="Other">{{ __('quotation.sale.payment_options.other') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="paid_amount">{{ __('quotation.sale.amount_received') }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input id="paid_amount" type="text" class="form-control" name="paid_amount" value= {{ $sale->with_invoice ? "$sale->total_amount" : "0" }} {{ $sale->with_invoice ? 'readonly' : 'required' }}>
                                            <div class="input-group-append">
                                                <button id="getTotalAmount" class="btn btn-primary" type="button">
                                                    <i class="bi bi-check-square"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="note">{{ __('quotation.form.note') }}</label>
                                <textarea name="note" id="note" rows="5" class="form-control"></textarea>
                            </div>

                            <input type="hidden" name="quotation_id" value="{{ $quotation_id }}">

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('quotation.sale.create_sale') }} <i class="bi bi-check"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script src="{{ asset('js/jquery-mask-money.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#paid_amount').maskMoney({
                prefix:'{{ settings()->currency->symbol }}',
                thousands:'{{ settings()->currency->thousand_separator }}',
                decimal:'{{ settings()->currency->decimal_separator }}',
                allowZero: true,
            });

            $('#getTotalAmount').click(function () {
                $('#paid_amount').maskMoney('mask', {{ Cart::instance('sale')->total() }});
            });

            $('#sale-form').submit(function () {
                var paid_amount = $('#paid_amount').maskMoney('unmasked')[0];
                $('#paid_amount').val(paid_amount);
            });
        });
    </script>
@endpush
