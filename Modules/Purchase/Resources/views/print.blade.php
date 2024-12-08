<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('purchase.print.title') }}</title>
    <link rel="stylesheet" href="{{ public_path('b3/bootstrap.min.css') }}">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div style="text-align: left;margin-bottom: 25px;">
                <h4 style="margin-bottom: 20px;">
                    <img width="50" src="{{ public_path('images/logo-dark.png') }}" alt="Logo">
                    <span> {{ __('sales.print.reference') }}</span> <strong>{{ $purchase->reference }}</strong>
                </h4>

            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row mb-4">
                        <div style="width: 33%; display: inline-block; vertical-align: top;text-align: left">
                            <h4 class="mb-2" style="border-bottom: 1px solid #dddddd;padding-bottom: 10px;">{{ __('purchase.print.company_info') }}:</h4>
                            <div><strong>{{ settings()->company_name }}</strong></div>
                            <div>{{ settings()->company_address }}</div>
                            <div>{{ __('purchase.print.email') }}: {{ settings()->company_email }}</div>
                            <div>{{ __('purchase.print.phone') }}: {{ settings()->company_phone }}</div>
                        </div>

                        <div style="width: 33%; display: inline-block; vertical-align: top;text-align: left">
                            <h4 class="mb-2" style="border-bottom: 1px solid #dddddd;padding-bottom: 10px;">{{ __('purchase.print.supplier_info') }}:</h4>
                            <div><strong>{{ $supplier->supplier_name }}</strong></div>
                            <div>{{ $supplier->address }}</div>
                            <div>{{ __('purchase.print.email') }}: {{ $supplier->supplier_email }}</div>
                            <div>{{ __('purchase.print.phone') }}: {{ $supplier->supplier_phone }}</div>
                        </div>

                        <div style="width: 32%; display: inline-block; vertical-align: top;text-align: left">
                            <h4 class="mb-2" style="border-bottom: 1px solid #dddddd;padding-bottom: 10px;">{{ __('purchase.print.invoice_info') }}:</h4>
                            <div>{{ __('purchase.print.invoice') }}: <strong>INV/{{ $purchase->reference }}</strong></div>
                            <div>{{ __('purchase.print.date') }}: {{ \Carbon\Carbon::parse($purchase->date)->format('d M, Y') }}</div>
                            <div>{{ __('purchase.print.status') }}: <strong>{{ $purchase->status }}</strong></div>
                            <div>{{ __('purchase.print.payment_status') }}: <strong>{{ $purchase->payment_status }}</strong></div>
                        </div>

                    </div>

                    <div class="table-responsive-sm" style="margin-top: 30px;">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th class="align-middle">{{ __('purchase.print.table.product') }}</th>
                                <th class="align-middle">{{ __('purchase.print.table.net_unit_price') }}</th>
                                <th class="align-middle">{{ __('purchase.print.table.quantity') }}</th>
                                <th class="align-middle">{{ __('purchase.print.table.discount') }}</th>
                                <th class="align-middle">{{ __('purchase.print.table.tax') }}</th>
                                <th class="align-middle">{{ __('purchase.print.table.sub_total') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($purchase->purchaseDetails as $item)
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
                        <div class="col-xs-4 col-xs-offset-8">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td class="left"><strong>{{ __('purchase.print.discount') }} ({{ $purchase->discount_percentage }}%)</strong></td>
                                    <td class="right">{{ format_currency($purchase->discount_amount) }}</td>
                                </tr>
                                <tr>
                                    <td class="left"><strong>{{ __('purchase.print.tax') }} ({{ $purchase->tax_percentage }}%)</strong></td>
                                    <td class="right">{{ format_currency($purchase->tax_amount) }}</td>
                                </tr>
                                <tr>
                                    <td class="left"><strong>{{ __('purchase.print.shipping') }}</strong></td>
                                    <td class="right">{{ format_currency($purchase->shipping_amount) }}</td>
                                </tr>
                                <tr>
                                    <td class="left"><strong>{{ __('purchase.print.grand_total') }}</strong></td>
                                    <td class="right"><strong>{{ format_currency($purchase->total_amount) }}</strong></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- <div class="row" style="margin-top: 25px;">
                        <div class="col-xs-12">
                            <p style="font-style: italic;text-align: center">{{ settings()->company_name }} &copy; {{ date('Y') }}.</p>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
