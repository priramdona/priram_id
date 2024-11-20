<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Atur ukuran halaman */
        @media print {
            body {
                width: 80mm;
                margin: 0;
                padding: 0;
            }
        }

        /* Jika ingin diatur untuk tampilan layar */
        .page {
            width: 80mm;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 10px;
            box-sizing: border-box;
        }
        * {
            font-size: 12px;
            line-height: 18px;
            font-family: 'Ubuntu', sans-serif;
        }
        h2 {
            font-size: 16px;
        }
        td,
        th,
        tr,
        table {
            border-collapse: collapse;
        }
        tr {border-bottom: 1px dashed #ddd;}
        td,th {padding: 7px 0;width: 50%;}

        table {
            width: 100%;
            max-width: 100%;
        }
        tfoot tr th:first-child {text-align: left;}

        .centered {
            text-align: center;
            align-content: center;
        }
        small{font-size:11px;}

        @media print {
            * {
                font-size:12px;
                line-height: 20px;
            }
            td,th {padding: 5px 0;}
            .hidden-print {
                display: none !important;
            }
            tbody::after {
                content: '';
                display: block;
                page-break-after: always;
                page-break-inside: auto;
                page-break-before: avoid;
            }
        }
    </style>
    <script>
        function invokePrint() {
            if (typeof Android !== "undefined" && Android.printPage) {
                // Panggil metode print di Android
                Android.printPage();
            } else {
                console.log("Android interface not available");
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            invokePrint(); // Panggil fungsi ini otomatis saat halaman dimuat
        });
    </script>
</head>
<body>

    <div class="page">
        <div id="receipt-data">
            <div class="centered">
            <h2 style="margin-bottom: 5px">{{ settings()->company_name }}</h2>

            <p style="font-size: 11px;line-height: 15px;margin-top: 0">
                {{ settings()->company_email }}, {{ settings()->company_phone }}
                <br>{{ settings()->company_address }}
            </p>
        </div>
        <p>
            {{ __('sales.pos_receipt.date') }}: {{ \Carbon\Carbon::parse($sale->date)->format('d M, Y') }}<br>
            {{ __('sales.pos_receipt.reference') }}: {{ $sale->reference }}<br>
            {{ __('sales.pos_receipt.customer_name') }}: {{ $sale->customer_name }}
        </p>
        <table class="table-data">
            <tbody>
            @foreach($sale->saleDetails as $saleDetail)
                <tr>
                    <td colspan="2">
                        {{  ($saleDetail->product->product_name) }}
                        <br>
                        ({{ $saleDetail->quantity }} x {{ format_currency($saleDetail->price) }})
                    </td>
                    <td style="text-align:right;vertical-align:bottom">{{ format_currency($saleDetail->sub_total) }}</td>
                </tr>
            @endforeach

            @if($sale->tax_percentage > 0)
                <tr>
                    <th colspan="2" style="text-align:left">{{ __('sales.pos_receipt.tax_label') }} ({{ $sale->tax_percentage }}%)</th>
                    <th style="text-align:right">{{ format_currency($sale->tax_amount) }}</th>
                </tr>
            @endif
            @if($sale->discount_percentage > 0)
                <tr>
                    <th colspan="2" style="text-align:left">{{ __('sales.pos_receipt.discount_label') }} ({{ $sale->discount_percentage }}%)</th>
                    <th style="text-align:right">{{ format_currency($sale->discount_amount) }}</th>
                </tr>
            @endif
            @if($sale->shipping_amount > 0)
                <tr>
                    <th colspan="2" style="text-align:left">{{ __('sales.pos_receipt.shipping_label') }}</th>
                    <th style="text-align:right">{{ format_currency($sale->shipping_amount) }}</th>
                </tr>
            @endif
            <tr>
                <th colspan="2" style="text-align:left">{{ __('sales.pos_receipt.total_label') }}</th>
                <th style="text-align:right">{{ format_currency($sale->total_amount) }}</th>
            </tr>
            @if($sale->additional_paid_amount > 0)
            <tr>
                <th colspan="2" style="text-align:left">{{ __('sales.pos_receipt.additional_amount_label') }}</th>
                <th style="text-align:right">{{ format_currency($sale->additional_paid_amount) }}</th>
            </tr>
            @endif
            </tbody>
        </table>
        <table>
            <tbody>
                <tr style="background-color:#ddd;">
                    <td class="centered" style="padding: 5px;">
                        {{ __('sales.pos_receipt.payment.paid_by') }}: {{ $sale->payment_method }}
                    </td>
                    <td class="centered" style="padding: 5px;">
                        {{ __('sales.pos_receipt.payment.amount') }}: {{ format_currency($sale->total_paid_amount) }}
                    </td>
                </tr>
                <tr style="border-bottom: 0;">
                    <td class="centered" colspan="3">
                        {{ __('sales.pos_receipt.scan_label') }}
                    </td>
                </tr>
                <tr style="border-bottom: 0;">
                    <td class="centered" colspan="3">
                        <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
