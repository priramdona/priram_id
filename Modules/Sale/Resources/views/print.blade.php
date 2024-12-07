<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('sales.print.title') }}</title>
    <link rel="stylesheet" href="{{ public_path('b3/bootstrap.min.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div style="text-align: left;margin-bottom: 25px;">
                <img width="50" src="{{ public_path('images/logo-dark.png') }}" alt="Logo">
                <h4 style="margin-bottom: 20px;">
                    <span>{{ __('sales.print.reference') }}</span> <strong>{{ $sale->reference }}</strong>
                </h4>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row mb-4">
                        <div style="width: 33%; display: inline-block; vertical-align: top;">
                            <h4 class="mb-2" style="border-bottom: 1px solid #dddddd;padding-bottom: 10px;">{{ __('sales.print.info.company') }}</h4>
                            <div><strong>{{ settings()->company_name }}</strong></div>
                            <div>{{ settings()->company_address }}</div>
                            <div>{{ __('sales.print.customer.email') }}: {{ settings()->company_email }}</div>
                            <div>{{ __('sales.print.customer.phone') }}: {{ settings()->company_phone }}</div>
                        </div>
                        @if($customer)
                        <div style="width: 33%; display: inline-block; vertical-align: top; ">
                            <h4 class="mb-2" style="border-bottom: 1px solid #dddddd;padding-bottom: 10px;">{{ __('sales.print.info.customer') }}</h4>
                            <div><strong>{{ $customer->customer_name }}</strong></div>
                            <div>{{ $customer->address }}</div>
                            <div>{{ __('sales.print.customer.email') }}: {{ $customer->customer_email }}</div>
                            <div>{{ __('sales.print.customer.phone') }}: {{ $customer->customer_phone }}</div>
                        </div>
                        @else
                        <div style="width: 33%; display: inline-block; vertical-align: top;">
                            <h4 class="mb-2" style="border-bottom: 1px solid #dddddd;padding-bottom: 10px;">{{ __('sales.print.info.customer') }}</h4>
                            <div><strong>{{ __('sales.print.customer.not_registered') }}</strong></div>
                            <div>-</div>
                            <div>{{ __('sales.print.customer.email') }}: -</div>
                            <div>{{ __('sales.print.customer.phone') }}: -</div>
                        </div>
                        @endif

                        <div style="width: 33%; display: inline-block; vertical-align: top;">
                            <h4 class="mb-2" style="border-bottom: 1px solid #dddddd;padding-bottom: 10px;">{{ __('sales.print.info.invoice') }}</h4>
                            <div>{{ __('sales.print.invoice.number') }}: <strong>INV/{{ $sale->reference }}</strong></div>
                            <div>{{ __('sales.print.invoice.date') }}: <strong>{{ \Carbon\Carbon::parse($sale->date)->format('d M, Y') }}</strong></div>
                            <div>
                                {{ __('sales.print.invoice.status') }}: <strong>{{ $sale->status }}</strong>
                            </div>
                            <div>
                                {{ __('sales.print.invoice.payment_status') }}: <strong>{{ $sale->payment_status }}</strong>
                            </div>
                        </div>

                    </div>

                    <div class="table-responsive-sm">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th class="align-middle">{{ __("sales.print.table.product") }}</th>
                                <th class="align-middle">{{ __("sales.print.table.net_unit_price") }}</th>
                                <th class="align-middle">{{ __("sales.print.table.quantity") }}</th>
                                <th class="align-middle">{{ __("sales.print.table.discount") }}</th>
                                <th class="align-middle">{{ __("sales.print.table.tax") }}</th>
                                <th class="align-middle">{{ __("sales.print.table.sub_total") }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sale->saleDetails as $item)
                                <tr>
                                    <td class="align-middle">
                                        {{ $item->product_name }}<br>
                                        <strong>{{ $item->product_code }}</strong>
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
                        <div class="col-xs-4 col-xs-offset-8">
                            <table class="table">
                                <tbody>
                                    @if ($sale->discount_amount > 0)
                                    <tr>
                                        <td class="left"><strong>{{ __("sales.show.table.discount") }} ({{ $sale->discount_percentage }}%)</strong></td>
                                        <td class="right">{{ format_currency($sale->discount_amount) }}</td>
                                    </tr>
                                    @endif
                                    @if ($sale->discount_amount > 0)
                                    <tr>
                                        <td class="left"><strong>{{ __("sales.show.table.tax") }} ({{ $sale->tax_percentage }}%)</strong></td>
                                        <td class="right">{{ format_currency($sale->tax_amount) }}</td>
                                    </tr>
                                    @endif
                                    @if ($sale->discount_amount > 0)
                                    <tr>
                                        <td class="left"><strong>{{ __("sales.show.table.shipping") }}</strong></td>
                                        <td class="right">{{ format_currency($sale->shipping_amount) }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="left"><strong>{{ __("sales.show.table.total") }}</strong></td>
                                        <td class="right"><strong>{{ format_currency($sale->total_amount) }}</strong></td>
                                    </tr>
                                    {{-- @if ($sale->additional_paid_amount > 0) --}}
                                    <tr>
                                        <td class="left">{{ __("sales.show.table.additional_amount") }}</td>
                                        <td class="right">{{ format_currency($sale->additional_paid_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="left"><strong>{{ __("sales.show.table.grand_total") }}</strong></td>
                                        <td class="right"><strong>{{ format_currency($sale->total_paid_amount) }}</strong></td>
                                    </tr>
                                    {{-- @endif --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 25px;">
                        <div class="col-xs-12">
                            <p style="font-style: italic;text-align: center">{{ settings()->company_name }} &copy; {{ date('Y') }}.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.print(); // Memicu dialog cetak
    });
</script>
