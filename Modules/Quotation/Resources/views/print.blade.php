<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('quotation.print.title') }}</title>
    <link rel="stylesheet" href="{{ public_path('b3/bootstrap.min.css') }}">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div style="text-align: left;margin-bottom: 25px;">
                <h4 style="margin-bottom: 20px;">
                    <img width="50" src="{{ public_path('images/logo-dark.png') }}" alt="Logo">
                    <span>  {{ __('menu.quotations') }} {{ __('sales.print.reference') }}</span> <strong>{{ $quotation->reference }}</strong>
                </h4>

            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row mb-4">
                        <div style="width: 33%; display: inline-block; vertical-align: top;text-align: left">
                            <h4 class="mb-2" style="border-bottom: 1px solid #dddddd;padding-bottom: 10px;">{{ __('quotation.print.company_info') }}</h4>
                            <div><strong>{{ settings()->company_name }}</strong></div>
                            <div>{{ settings()->company_address }}</div>
                            <div>{{ __('quotation.print.email') }}: {{ settings()->company_email }}</div>
                            <div>{{ __('quotation.print.phone') }}: {{ settings()->company_phone }}</div>
                        </div>

                        <div style="width: 33%; display: inline-block; vertical-align: top;text-align: left">
                            <h4 class="mb-2" style="border-bottom: 1px solid #dddddd;padding-bottom: 10px;">{{ __('quotation.print.customer_info') }}</h4>
                            <div><strong>{{ $customer->customer_name }}</strong></div>
                            <div>{{ $customer->address }}</div>
                            <div>{{ __('quotation.print.email') }}: {{ $customer->customer_email }}</div>
                            <div>{{ __('quotation.print.phone') }}: {{ $customer->customer_phone }}</div>
                        </div>

                        <div style="width: 32%; display: inline-block; vertical-align: top;text-align: left;">
                            <h4 class="mb-2" style="border-bottom: 1px solid #dddddd;padding-bottom: 10px;">{{ __('quotation.print.invoice_info') }}</h4>
                            <div>{{ __('quotation.print.invoice') }}: <strong>INV/{{ $quotation->reference }}</strong></div>
                            <div>{{ __('quotation.print.date') }}: <strong>{{ \Carbon\Carbon::parse($quotation->date)->format('d M, Y') }}</strong></div>
                            <div>{{ __('quotation.print.status') }}: <strong>{{ $quotation->status }}</strong></div>
                            <div>{{ __('quotation.print.payment_status') }}: <strong>{{ $quotation->payment_status ?? 'Unpaid' }}</strong></div>
                        </div>

                    </div>

                    <div class="table-responsive-sm" style="margin-top: 30px;">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th class="align-middle">{{ __('quotation.print.table.product') }}</th>
                                <th class="align-middle">{{ __('quotation.print.table.net_unit_price') }}</th>
                                <th class="align-middle">{{ __('quotation.print.table.quantity') }}</th>
                                <th class="align-middle">{{ __('quotation.print.table.discount') }}</th>
                                <th class="align-middle">{{ __('quotation.print.table.tax') }}</th>
                                <th class="align-middle">{{ __('quotation.print.table.sub_total') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($quotation->quotationDetails as $item)
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
                                    <td class="left"><strong>{{ __('quotation.print.discount') }} ({{ $quotation->discount_percentage }}%)</strong></td>
                                    <td class="right">{{ format_currency($quotation->discount_amount) }}</td>
                                </tr>
                                <tr>
                                    <td class="left"><strong>{{ __('quotation.print.tax') }} ({{ $quotation->tax_percentage }}%)</strong></td>
                                    <td class="right">{{ format_currency($quotation->tax_amount) }}</td>
                                </tr>
                                <tr>
                                    <td class="left"><strong>{{ __('quotation.print.shipping') }}</strong></td>
                                    <td class="right">{{ format_currency($quotation->shipping_amount) }}</td>
                                </tr>
                                <tr>
                                    <td class="left"><strong>{{ __('quotation.print.grand_total') }}</strong></td>
                                    <td class="right"><strong>{{ format_currency($quotation->total_amount) }}</strong></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- <div class="row" style="margin-top: 25px;">
                        <div class="col-xs-12">
                            <p style="font-style: italic;text-align: center">{{ settings()->company_name }} &copy; {{ date('Y') }}</p>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
