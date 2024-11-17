@extends('layouts.app')

@section('title', 'Create Quotation')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('quotation.breadcrumb.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}">{{ __('quotation.breadcrumb.quotations') }}</a></li>
        <li class="breadcrumb-item active">{{ __('quotation.breadcrumb.add_quotation') }}</li>
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
                        <form id="quotation-form" action="{{ route('quotations.store') }}" method="POST">
                            @csrf

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="reference">{{ __('quotation.form.reference') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="reference" required readonly value="QT">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="from-group">
                                        <div class="form-group">
                                            <label for="customer_id">{{ __('quotation.form.customer') }} <span class="text-danger">*</span></label>
                                            <select class="form-control" name="customer_id" id="customer_id" required>
                                                @foreach(\Modules\People\Entities\Customer::where('business_id',Auth::user()->business_id)->get() as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
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

                            <livewire:product-cart :cartInstance="'quotation'"/>

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="status">{{ __('quotation.form.status') }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="status" id="status" required>
                                            <option value="Pending">{{ __('quotation.form.status_options.pending') }}</option>
                                            <option value="Sent">{{ __('quotation.form.status_options.sent') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                              <!-- Checkbox untuk Send Invoice -->
                              <div class="card">
                                <div class="card-body">
                                    <div>
                                        <label for="with_invoice">
                                            <input type="checkbox" id="with_invoice" name="with_invoice" value="1" onchange="toggleExpiryDate()"
                                                {{ old('with_invoice') ? 'checked' : '' }}>
                                            {{ __('quotation.form.send_invoice') }}
                                        </label>
                                    </div>

                                    <!-- Input untuk Invoice Expiry Date yang muncul jika Send Invoice dicentang -->
                                    <div id="expiry_date_field" style="display: none;">
                                        <label for="invoice_expiry_date">{{ __('quotation.form.invoice_expiry_date') }}:</label>
                                        <input type="date" id="invoice_expiry_date" name="invoice_expiry_date" value="{{ old('invoice_expiry_date') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center">

                            </div>
                            <div class="form-group">
                                <label for="note">{{ __('quotation.form.note') }}</label>
                                <textarea name="note" id="note" rows="5" class="form-control"></textarea>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('quotation.form.create_quotation') }} <i class="bi bi-check"></i>
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
<script>
    // Mencegah form di-submit saat Enter ditekan
    document.getElementById('quotation-form').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();  // Mencegah submit
        }
    });

    function toggleExpiryDate() {
        const sendInvoice = document.getElementById('with_invoice');
        const expiryDateField = document.getElementById('expiry_date_field');
        expiryDateField.style.display = sendInvoice.checked ? 'block' : 'none';
    }

    // Cek status pada load page agar tampil sesuai kondisi lama
    document.addEventListener('DOMContentLoaded', function () {
        toggleExpiryDate();
    });
</script>
@endpush
