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
                                        <img src="{{ $channel->image_url }}" alt="Product Image" class="img-fluid img-thumbnail mb-2">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="product-details">
                                        <div class="detail-item">
                                            <span class="detail-label">Code</span>
                                            <span class="detail-value">{{ $channel->code }}</span>
                                        </div>

                                        <div class="detail-item">
                                            <span class="detail-label">Name</span>
                                            <span class="detail-value">{{ $channel->name }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Category</span>
                                            <span class="detail-value">{{ $channel->type }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Source</span>
                                            <span class="detail-value">Xendit</span>
                                        </div>

                                        <div class="detail-item">
                                            <span class="detail-label">Payment Fee 1</span>
                                            @if($channel->fee_type_1 == "%")
                                                <span class="detail-value">{{ round($channel->fee_value_1, 2) }} %</span>
                                            @else
                                                <span class="detail-value">{{ format_currency($channel->fee_value_1) }}</span>
                                            @endif
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Payment Fee 2</span>
                                            @if($channel->fee_type_2 == "%")
                                                <span class="detail-value">{{ round($channel->fee_value_2, 2) }} %</span>
                                            @else
                                                <span class="detail-value">{{ format_currency($channel->fee_type_2) }}</span>
                                            @endif
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Is PPN (From Nominal Fees)</span>
                                            @if($channel->is_ppn)
                                            <span class="detail-value"> <i class="bi bi-check bg-success"></i> {{ round($configs->ppn_value, 2) }} %</span>
                                            @else
                                                <span class="detail-value"> <i class="bi bi-x bg-danger"></i></span>
                                            @endif
                                        </div>

                                        <div class="detail-item">
                                            <span class="detail-label">Amount Range</span>
                                            <span class="detail-value">{{ format_currency($channel->min) }} - {{ format_currency($channel->max) }}</span>

                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Process</span>
                                            <span class="detail-value">{{ $channel->payment_process }}</span>

                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Settlement</span>
                                            <span class="detail-value">{{ $channel->settlement }} Days</span>

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


