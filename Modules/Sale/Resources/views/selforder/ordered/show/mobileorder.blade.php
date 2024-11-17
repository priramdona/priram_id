@extends('layouts.app')

@section('title', 'Create Sale From Quotation')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('selforder.mobileorder.index') }}">{{ __('sales.breadcrumb.selforders') }}</a></li>
        <li class="breadcrumb-item active">{{ __('sales.breadcrumb.make_sale') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">

        @php
            $invoiceInfo = \Modules\PaymentMethod\Entities\PaymentMethod::find($payment->payment_method_id);
            $paymentChannel = \Modules\PaymentMethod\Entities\PaymentChannel::find($payment->payment_channel_id);
        @endphp
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
                                        <label for="reference">{{ __('sales.edit.form.reference') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="reference" required readonly value="{{ $selforder_type->code ?? "SOM" }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="from-group">
                                        <div class="form-group">
                                            <label for="customer_id">{{ __('sales.edit.form.customer') }} <span class="text-danger">*</span></label>
                                            <select class="form-control" name="customer_id" id="customer_id" readonly>
                                                {{-- @foreach(\Modules\People\Entities\Customer::where('business_id', Auth::user()->business_id)->get() as $customer) --}}
                                                    <option selected value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                                {{-- @endforeach --}}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="from-group">
                                        <div class="form-group">
                                            <label for="date">{{ __('sales.edit.form.date') }} <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="date" readonly value="{{ \Carbon\Carbon::parse($sale->date)->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <livewire:product-cart :cartInstance="'sale'" :data="$sale"/>

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="status">{{ __('sales.edit.form.status.label') }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="status" id="status" required>
                                            <option value="Pending">{{ __('sales.edit.form.status.pending') }}</option>
                                            <option value="Completed">{{ __('sales.edit.form.status.completed') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="from-group">
                                        <div class="form-group">
                                            <label for="payment_method">{{ __('sales.edit.form.payment_method') }} <span class="text-danger">*</span></label>
                                            <select class="form-control" name="payment_method" id="payment_method" readonly>
                                                <option selected value="{{ $invoiceInfo->id ?? '' }}">{{ $invoiceInfo->name ?? '' }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @if($paymentChannel)
                                <div class="col-lg-4">
                                    <div class="from-group">
                                        <div class="form-group">

                                            <label for="payment_channel">{{ __('sales.edit.form.payment_channel') }} <span class="text-danger">*</span></label>
                                            <select class="form-control" name="payment_channel" id="payment_channel" readonly>
                                                <option selected value="{{ $paymentChannel->id }}">{{ $paymentChannel->name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-lg-4" {{ $paymentChannel ? 'hidden' : ''}}>
                                    <div class="form-group">
                                        <label for="paid_amount">{{ __('sales.edit.form.amount_received') }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input id="paid_amount" type="text" class="form-control" name="paid_amount" value= "{{ $paymentChannel ? format_currency($sale->paid_amount) : 0 }}" {{ $paymentChannel ? 'readonly' : 'required' }}>
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
                                <label for="note">{{ __('sales.edit.form.note') }}</label>
                                <textarea name="note" id="note" rows="5" class="form-control"></textarea>
                            </div>

                            <input type="hidden" name="selforder_checkout_id" value="{{ $selforder_checkout_id }}">

                            <div class="mt-3">
                                <button id="proccedaction" name="proccedaction" type="submit" class="btn btn-primary" {{ $paymentChannel ? 'hidden' : '' }}>
                                    {{ __('sales.edit.form.create') }} <i class="bi bi-check"></i>
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
        const lang = {
            salesVerified: @json(__('sales.show.table.verified')),
        };
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


        function generatePhone(id, productName) {
            // var phoneNumber = document.getElementById('phone_number_' + id).value;
            var generateButton = document.getElementById('generate_button_' + id);
            generateButton.classList.remove('btn-primary');
            generateButton.classList.add('btn-success');
            generateButton.innerHTML = '<i class="bi bi-check"></i> ' + lang.salesVerified;
            generateButton.disabled = true;

            checkAllGenerated();
        }

        function checkAllGenerated() {
            // Dapatkan semua tombol Generate
            var buttons = document.querySelectorAll('button[id^="generate_button_"]');
            var allGenerated = true;

            // Cek apakah semua tombol sudah di-disable
            buttons.forEach(function(button) {
                if (!button.disabled) {
                    allGenerated = false;
                }
            });

            // Tampilkan tombol Proceed jika semua tombol sudah di-disable
            var proceedButton = document.getElementById('proccedaction');
            if (allGenerated) {
                proceedButton.hidden = false;
            } else {
                proceedButton.hidden = true;
            }
        }
    </script>
@endpush
