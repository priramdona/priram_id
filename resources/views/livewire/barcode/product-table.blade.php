<div>
    @if (session()->has('message'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="alert-body">
                <span>{{ session('message') }}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mobile-table">
                    <thead>
                    <tr>
                        <th>{{__('products.barcode.datatable.name') }}</th>
                        <th>{{__('products.barcode.datatable.code') }}</th>
                        <th>
                            {{__('products.barcode.datatable.quantity') }}
                            <i class="bi bi-question-circle-fill text-info" data-toggle="tooltip" data-placement="top" title="{{__('products.barcode.datatable.max_tooltip') }}"></i>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($product))
                        <tr>
                            <td data-label="{{__('products.barcode.datatable.name') }}" class="align-right">{{ $product->product_name }}</td>
                            <td data-label="{{__('products.barcode.datatable.code') }}" class="align-right">{{ $product->product_code }}</td>
                            <td data-label="{{__('products.barcode.datatable.quantity') }}" class="align-right">
                                <input wire:model.live="quantity" class="form-control quantity-input" class="align-right" type="number" min="1" max="100" value="{{ $quantity }}">
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="3" class="text-center">
                                <span class="text-danger">{{ __('products.barcode.info_table') }}</span>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <button wire:click="generateBarcodes({{ $product }}, {{ $quantity }})" type="button" class="btn btn-primary">
                    <i class="bi bi-upc-scan"></i> {{ __('products.barcode.generate') }}
                </button>
            </div>
        </div>
    </div>

    <div wire:loading wire:target="generateBarcodes" class="w-100">
        <div class="d-flex justify-content-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">{{ __('products.barcode.load') }}</span>
            </div>
        </div>
    </div>

    @if(!empty($barcodes))
        <div class="text-right mb-3">
            <button wire:click="getPdf" wire:loading.attr="disabled" type="button" class="btn btn-primary">
                <span wire:loading wire:target="getPdf" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                <i wire:loading.remove wire:target="getPdf" class="bi bi-file-earmark-pdf"></i> {{ __('products.barcode.download_pdf') }}
            </button>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    @foreach($barcodes as $barcode)
                        <div class="col-lg-3 col-md-4 col-sm-6" style="border: 1px solid #ffffff;border-style: dashed;background-color: #48FCFE;">
                            <p class="mt-3 mb-1" style="font-size: 15px;color: #000;">
                                {{ $product->product_name }}
                            </p>
                            <div>
                                {!! $barcode !!}
                            </div>
                            <p style="font-size: 15px;color: #000;">
                                {{ __('products.barcode.price') }}{{ format_currency($product->product_price) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>


@push('page_css')
<style>
@media screen and (max-width: 767px) {
    .mobile-table thead {
        display: none;
    }

    .mobile-table,
    .mobile-table tbody,
    .mobile-table tr,
    .mobile-table td {
        display: flex;
        flex-direction: row;
        width: 100%;
    }

    .mobile-table tr {
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        overflow: hidden;
        display: flex;
        flex-direction: row;
    }

    .mobile-table td {
        padding: 0.75rem;
        border-top: none;
        flex: 1;
        text-align: left;
        position: relative;
    }

    .mobile-table td::before {
        display: none; /* Remove the before content for mobile to simplify layout */
    }

    .align-right {
        text-align: right;
    }

    .quantity-input {
        width: 100px;
        display: inline-block;
        max-width: 100px;
    }
}

@media screen and (max-width: 480px) {
    .mobile-table {
        display: block;
        width: 100%;
    }

    .mobile-table td {
        display: block;
        text-align: center;
    }

    .align-right {
        text-align: center;
    }

    .quantity-input {
        width: 100%;
        max-width: none;
    }
}

</style>
@endpush
