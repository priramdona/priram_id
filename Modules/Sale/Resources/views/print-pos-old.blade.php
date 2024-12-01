<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
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

        table {width: 100%;}
        tfoot tr th:first-child {text-align: left;}

        .centered {
            text-align: center;
            align-content: center;
        }
        small{font-size:8px;}

        @media print {
            * {
                font-size:8px;
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
        // Ambil data untuk dikirimkan ke android studio
        var saleData = "{{ $sale ?? '' }}";
        var saleDetails = "{{ $saleDetails ?? '' }}"; //array
        var qrcode = "{{ $qrcode ?? '' }}"; //linkQRCode untuk ditampilkan

        document.addEventListener('DOMContentLoaded', function () {
            if (saleData !== ''){
                if (window.AndroidInterface) {
                    window.AndroidInterface.
                    window.AndroidInterface.dataInvoice(saleData, saleDetails, qrcode); // Mengirim ke Android interface
                }else {
                    alert("Android interface not available");
                }
            }
        });
    </script>
</head>
<body>

<div style="max-width:400px;margin:0 auto">
    <div id="receipt-data">
        <div class="centered">
            <h2 style="margin-bottom: 5px">{{ settings()->company_name }}</h2>

            <p style="font-size: 11px;line-height: 15px;margin-top: 0">
                {{ settings()->company_email }}, {{ settings()->company_phone }}
                <br>{{ settings()->company_address }}
            </p>
        </div>
        <p>
            {{ __('sales.pos_receipt.date') }} : {{ \Carbon\Carbon::parse($sale->date)->format('d M, Y') }}<br>
            {{ __('sales.pos_receipt.reference') }} : {{ $sale->reference }}<br>
            {{ __('sales.pos_receipt.customer_name') }} : {{ $sale->customer_name ?? 'Tidak terdaftar'}}
        </p>
        <table class="table-data">
            <tbody>
            @foreach($sale->saleDetails as $saleDetail)
                <tr>
                    <td colspan="2"  style="width: 60%;">
                        {{ $saleDetail->product->product_name }}
                        <strong>({{ $saleDetail->quantity }} x {{ str_replace('Rp. ','',format_currency($saleDetail->price)) }}</strong>)
                    </td>
                    <td style="width: 40%;text-align:right;vertical-align:bottom">{{ format_currency($saleDetail->sub_total) }}</td>
                </tr>
            @endforeach

            @if($sale->tax_percentage > 0)
            <tr>
                <th colspan="2" style="text-align:left;font-size: 10px;">{{ __('sales.pos_receipt.tax_label') }} ({{ $sale->tax_percentage }}%)</th>
                <th style="text-align:right;">{{ format_currency($sale->tax_amount) }}</th>
            </tr>
            @endif
            @if($sale->discount_percentage > 0)
                <tr>
                    <th colspan="2" style="text-align:left;font-size: 10px;">{{ __('sales.pos_receipt.discount_label') }} ({{ $sale->discount_percentage }}%)</th>
                    <th style="text-align:right;">{{ format_currency($sale->discount_amount) }}</th>
                </tr>
            @endif
            @if($sale->shipping_amount > 0)
                <tr>
                    <th colspan="2" style="text-align:left;">{{ __('sales.pos_receipt.shipping_label') }}</th>
                    <th style="text-align:right;">{{ format_currency($sale->shipping_amount) }}</th>
                </tr>
            @endif
            <tr>
                <th colspan="2" style="text-align:left;">{{ __('sales.pos_receipt.total_label') }}</th>
                <th style="text-align:right;">{{ format_currency($sale->total_amount) }}</th>
            </tr>
            @if($sale->additional_paid_amount > 0)
            <tr >
                <th colspan="2" style="text-align:left;">{{ __('sales.pos_receipt.additional_amount_label') }}</th>
                <th style="text-align:right;">{{ format_currency($sale->additional_paid_amount) }}</th>
            </tr>
            @endif
            </tbody>
        </table>
        <table>
            <tbody>
                <tr style="background-color:#ddd;">
                    <td class="centered" style="font-weight: bold;">
                        {{ __('sales.pos_receipt.payment.paid_by') }}<br> {{ $sale->payment_method }}
                    </td>
                    <td class="centered" style="font-weight: bold;">
                        {{ __('sales.pos_receipt.payment.amount') }}<br> {{ format_currency($sale->total_paid_amount) }}
                    </td>
                </tr>
                <tr style="border-bottom: 0;">
                    <td class="centered" colspan="3" style="font-size: 10px;">
                        {{ __('sales.pos_receipt.scan_label') }}
                    </td>
                </tr>
                <tr style="border-bottom: 0;">
                    <td class="centered" colspan="3">
                        <img src="data:image/png;base64,{{ $qrcode }}" alt="Barcode" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
