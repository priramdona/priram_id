<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') || {{ config('app.name') }}</title>
    <meta content="Kasir Mulia" name="author">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">

    @include('includes.main-css')

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
    <!-- Tambahkan styling untuk garis pembatas -->
    <style>
        #interactive {
            position: relative;
            width: 80%;
            max-width: 640px;
            height: 100px;
            margin: 0 auto;
            border: 2px solid #000;
        }

        #video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Garis pembatas berbentuk persegi panjang di tengah layar */
        .scanner-laser {
            position: absolute;
            border: 2px solid red;
            width: 80%;
            height: 80%;
            top: 10%;
            left: 10%;
            z-index: 2;
            box-shadow: 0 0 10px red;
        }

        /* Styling hasil scanning */
        #scanned-result {
            font-size: 18px;
            color: green;
            text-align: center;
            margin-top: 20px;
        }
    </style>

    <!-- Masukkan QuaggaJS dari CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
  <!-- v2.0.0 -->
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/bootstrap/css/bootstrap.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/modules/core/common/core.cleanui.css') !!}">

</head>

<body class="c-app">

    <div class="c-wrapper">


        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid mb-4">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    @forelse($product->getMedia('images') as $media)
                                        <img src="{{ $media->getUrl() }}" alt="Product Image" class="img-fluid img-thumbnail mb-2">
                                    @empty
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
                                            <span class="detail-label">{{ __('products.product_name') }}</span>
                                            <span class="detail-value">{{ $product->product_name }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">{{ __('products.unit') }}</span>
                                            <span class="detail-value">{{ $product->product_unit }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">{{ __('products.category') }}</span>
                                            <span class="detail-value">{{ $product->category->category_name }}</span>
                                        </div>

                                        <div class="detail-item">
                                            <span class="detail-label">{{ __('products.price') }}</span>
                                            <span class="detail-value">{{ format_currency($product->product_price) }}</span>
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
                                        <div class="detail-item text-center" >
                                            {{-- <span class="detail-label">{{ __('products.barcode_name') }}</span> --}}
                                            <span class="detail-value">{!! \Milon\Barcode\Facades\DNS1DFacade::getBarCodeSVG($product->product_code, $product->product_barcode_symbology, 2, 110) !!}</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </main>
        </div>

        @include('layouts.footer')
    </div>

    @include('includes.main-js')
</body>
</html>


