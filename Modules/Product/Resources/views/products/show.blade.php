@extends('layouts.app')

@section('title', 'Product Details')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
        <li class="breadcrumb-item active">Details</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        {{-- <div class="row mb-3">
            <div class="col-md-12">
                {!! \Milon\Barcode\Facades\DNS1DFacade::getBarCodeSVG($product->product_code, $product->product_barcode_symbology, 2, 110) !!}
            </div>
        </div> --}}
        <div class="row">
            <div class="col-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        @forelse($product->getMedia('images') as $media)
                        {{-- {{ dd($product->getFirstMediaUrl('images', 'thumb')) }} --}}
                            <img src="{{ $media->getUrl() }}" alt="Product Image" class="img-fluid img-thumbnail mb-2">
                        @empty
                        {{-- {{ dd($product->media->toArray()) }} --}}

                            <img src="{{ $product->getFirstMediaUrl('images') }}" alt="Product Image" class="img-fluid img-thumbnail mb-2">
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="product-details">
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.code') }}</span>
                                <span class="detail-value">{{ $product->product_code }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.barcode_symbology') }}</span>
                                <span class="detail-value">{{ $product->product_barcode_symbology }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.product_name') }}</span>
                                <span class="detail-value">{{ $product->product_name }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.category') }}</span>
                                <span class="detail-value">{{ $product->category->category_name }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.cost') }}</span>
                                <span class="detail-value">{{ format_currency($product->product_cost) }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.price') }}</span>
                                <span class="detail-value">{{ format_currency($product->product_price) }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.quantity') }}</span>
                                <span class="detail-value">{{ $product->product_quantity . ' ' . $product->product_unit }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.stock_worth') }}</span>
                                <span class="detail-value">
                                    {{ __('products.cost') }}: {{ format_currency($product->product_cost * $product->product_quantity) }}<br>
                                    {{ __('products.price') }}: {{ format_currency($product->product_price * $product->product_quantity) }}<br>
                                    {{ __('products.profit') }}: {{ format_currency(($product->product_price * $product->product_quantity) - ($product->product_cost * $product->product_quantity)) }}
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.alert_quantity') }}</span>
                                <span class="detail-value">{{ $product->product_stock_alert }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.tax') }} (%)</span>
                                <span class="detail-value">{{ $product->product_order_tax ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.tax_type') }}</span>
                                <span class="detail-value">
                                    @if($product->product_tax_type == 1)
                                        Exclusive
                                    @elseif($product->product_tax_type == 2)
                                        Inclusive
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.note') }}</span>
                                <span class="detail-value">{{ $product->product_note ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.barcode_name') }}</span>
                                <span class="detail-value">{!! \Milon\Barcode\Facades\DNS1DFacade::getBarCodeSVG($product->product_code, $product->product_barcode_symbology, 2, 110) !!}</span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection

@push('page_css')
<style>
    .product-details {
        display: flex;
        flex-wrap: wrap;
    }
    .detail-item {
        width: 100%;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }
    .detail-label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }
    .detail-value {
        display: block;
    }
    @media (min-width: 768px) {
        .detail-item {
            width: 50%;
            padding-right: 15px;
        }
    }
    @media (min-width: 992px) {
        .detail-item {
            width: 33.33%;
        }
    }
</style>
@endpush
