<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>@yield('title') || {{ config('app.name') }}</title>
    <meta content="Munggi Priramdona" name="author">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">


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
<body>

<div class="cat__content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">Info Usaha:</h5>
                                <div><strong>{{ settings()->company_name }}</strong></div>
                                <div>{{ settings()->company_address }}</div>
                                <div>Email: {{ settings()->company_email }}</div>
                                <div>Telepon: {{ settings()->company_phone }}</div>
                            </div>
                            @if($customer)
                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">Info Pelanggan:</h5>
                                <div><strong>{{ $customer->customer_name }}</strong></div>
                                <div>{{ $customer->address }}</div>
                                <div>Email: {{ $customer->customer_email }}</div>
                                <div>Telepon: {{ $customer->customer_phone }}</div>
                            </div>
                            @else
                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">Info Pelanggan:</h5>
                                <div><strong>Tidak Terdaftar</strong></div>
                                <div>-</div>
                                <div>Email: -</div>
                                <div>Telepon: -</div>
                            </div>
                            @endif
                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">Info Faktur:</h5>
                                <div>Faktur: <strong>INV/{{ $sale->reference }}</strong></div>
                                <div>Tanggal: {{ \Carbon\Carbon::parse($sale->date)->format('d M, Y') }}</div>
                                <div>
                                    Status: <strong>{{ $sale->status }}</strong>
                                </div>
                                <div>
                                    Status Pembayaran: <strong>{{ $sale->payment_status }}</strong>
                                </div>
                            </div>

                        </div>

                        <div class="table-responsive-sm">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th class="align-middle">Produk</th>
                                    <th class="align-middle">Harga unit</th>
                                    <th class="align-middle">Jumlah</th>
                                    <th class="align-middle">Diskon</th>
                                    <th class="align-middle">Pajak</th>
                                    <th class="align-middle">Sub Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sale->saleDetails as $item)
                                    <tr>
                                        <td class="align-middle">
                                            {{ $item->product_name }} <br>
                                            <span class="badge badge-success">
                                                {{ $item->product_code }}
                                            </span>
                                        </td>

                                        <td class="align-middle">{{ format_currency($item->unit_price) }}</td>

                                        <td class="align-middle">
                                            {{ $item->quantity }}
                                        </td>

                                        <td class="align-middle">
                                            {{ format_currency($item->product_discount_amount) }}
                                        </td>

                                        <td class="align-middle">
                                            {{ format_currency($item->product_tax_amount) }}
                                        </td>

                                        <td class="align-middle">
                                            {{ format_currency($item->sub_total) }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-5 ml-md-auto">
                                <table class="table">
                                    <tbody>
                                    @if ($sale->discount_amount > 0)
                                    <tr>
                                        <td class="left"><strong>Diskon ({{ $sale->discount_percentage }}%)</strong></td>
                                        <td class="right">{{ format_currency($sale->discount_amount) }}</td>
                                    </tr>
                                    @endif
                                    @if ($sale->discount_amount > 0)
                                    <tr>
                                        <td class="left"><strong>Pajak ({{ $sale->tax_percentage }}%)</strong></td>
                                        <td class="right">{{ format_currency($sale->tax_amount) }}</td>
                                    </tr>
                                    @endif
                                    @if ($sale->discount_amount > 0)
                                    <tr>
                                        <td class="left"><strong>Pengiriman</strong></td>
                                        <td class="right">{{ format_currency($sale->shipping_amount) }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="left"><strong>Total</strong></td>
                                        <td class="right"><strong>{{ format_currency($sale->total_amount) }}</strong></td>
                                    </tr>
                                    {{-- @if ($sale->additional_paid_amount > 0) --}}
                                    <tr>
                                        <td class="left">Biaya tambahan</td>
                                        <td class="right">{{ format_currency($sale->additional_paid_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="left"><strong>Total Keseluruhan</strong></td>
                                        <td class="right"><strong>{{ format_currency($sale->total_paid_amount) }}</strong></td>
                                    </tr>
                                    {{-- @endif --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {

            $("#cart-checkout").steps({
                headerTag: "h3",
                bodyTag: "section",
                transitionEffect: 0,
                autoFocus: true
            });

        });
    </script>
    <!-- END: page scripts -->
    </div>
    </body>
    </html>
