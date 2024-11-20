<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @media print {
            @page {
                size: 200px auto; /* Lebar tetap 80mm, tinggi menyesuaikan konten */
                margin: 0; /* Hilangkan margin otomatis */
            }
            body {
                margin: 0;
                padding: 0;
                width: 200px;
            }
            .page {
                width: 200px;
                margin: 0 auto;
                overflow: visible;
                page-break-inside: avoid;
            }
            table, tbody, tr, td {
                page-break-inside: avoid; /* Hindari pemutusan tabel */
            }
        }

        * {
            font-size: 6px;
            line-height: 18px;
            font-family: 'Arial', sans-serif; /* Gunakan font sans-serif untuk kejelasan */
        }

        h2 {
            font-size: 10px;
            margin: 0;
        }


        .centered {
            text-align: center;
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

<div class="page" style="max-width:200px;margin:0 auto">
        <div id="receipt-data" style="width: 70%;">
            <div class="centered">
                <h2>{{ settings()->company_name }}</h2>

                <p style="font-size: 8px;line-height: 8px;margin-top: 0">
                    {{ settings()->company_email }}
                    <br>{{ settings()->company_phone }}
                    <br>{{ settings()->company_address }}
                </p>
            </div>
            <p>
            {{ __('sales.pos_receipt.date') }}: {{ \Carbon\Carbon::parse($sale->date)->format('d M, Y') }}<br>
            {{ __('sales.pos_receipt.reference') }}: {{ $sale->reference }}<br>
            {{ __('sales.pos_receipt.customer_name') }}: {{ $sale->customer_name }}
        </p>
        <table  border="0" cellpadding="0" cellspacing="0" style="width: 90%; table-layout: fixed; border-collapse: collapse; font-family: Arial, sans-serif;">
            <tbody>
            @foreach($sale->saleDetails as $saleDetail)
                <tr >
                    <td style="width: 60%; text-align: left;border-top: 1px Dotted #514d6a; font-size: 6px;">
                        {{  ($saleDetail->product->product_name) }}
                        <br>
                        ({{ $saleDetail->quantity }} x {{ str_replace('.00','',str_replace('Rp. ','',format_currency($saleDetail->price))) }})
                    </td>
                    <td style="width: 40%; text-align: right;vertical-align:bottom; border-top: 1px Dotted #514d6a; font-size: 6px;">{{ str_replace('.00','',str_replace('Rp. ','',format_currency($saleDetail->sub_total))) }}</td>
                </tr>
            @endforeach

            @if($sale->tax_percentage > 0)
            <tr >
                    <td style="width: 60%; text-align: left; ">{{ __('sales.pos_receipt.tax_label') }} ({{ $sale->tax_percentage }}%)</td>
                    <td style="width: 40%; text-align: right;">{{ format_currency($sale->tax_amount) }}</td>
                </tr>
            @endif
            @if($sale->discount_percentage > 0)
                <tr>
                    <td style="width: 60%; text-align: left;">{{ __('sales.pos_receipt.discount_label') }} ({{ $sale->discount_percentage }}%)</td>
                    <td style="width: 40%; text-align: right;">{{ format_currency($sale->discount_amount) }}</td>
                </tr>
            @endif
            @if($sale->shipping_amount > 0)
                <tr>
                    <td style="width: 60%; text-align: left;">{{ __('sales.pos_receipt.shipping_label') }}</td>
                    <td style="width: 40%; text-align: right;">{{ format_currency($sale->shipping_amount) }}</td>
                </tr>
            @endif
            <tr>
                <td style="width: 60%; text-align: left;">{{ __('sales.pos_receipt.total_label') }}</td>
                <td style="width: 40%; text-align: right;">{{ format_currency($sale->total_amount) }}</td>
            </tr>
            @if($sale->additional_paid_amount > 0)
            <tr>
                <td style="width: 60%; text-align: left;">{{ __('sales.pos_receipt.additional_amount_label') }}</td>
                <td style="width: 40%; text-align: right;">{{ format_currency($sale->additional_paid_amount) }}</td>
            </tr>
            @endif
            </tbody>
        </table>
        <table  border="0" cellpadding="0" cellspacing="0" style="width: 90%; table-layout: fixed; border-collapse: collapse; font-family: Arial, sans-serif;">
            <tbody>
                <tr>
                    <td style="width: 60%; text-align: left; font-weight: bold; border-top: 1px solid #514d6a; ">
                        {{ __('sales.pos_receipt.payment.paid_by') }}: {{ $sale->payment_method }}
                    </td>
                    <td style="width: 40%; text-align: right;font-weight: bold; border-top: 1px solid #514d6a;">
                       {{ format_currency($sale->total_paid_amount) }}
                    </td>
                </tr>

            </tbody>
        </table>
        <table  border="0" cellpadding="0" cellspacing="0" style="width: 90%; table-layout: fixed; border-collapse: collapse; font-family: Arial, sans-serif;">
            <tbody>
                <tr style="border-bottom: 0;">
                    <td style="width: 100%; text-align: center; ">
                        {{ __('sales.pos_receipt.scan_label') }}
                    </td>
                </tr>
                <tr style="border-bottom: 0;">
                    <td style="width: 100%; text-align: center; ">
                        <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode" style="width: 50px; height: 50px;" />
                    </td>
                </tr>
                <tr style="border-bottom: 0;">
                    <td style="width: 100%; text-align: center; ">
                        {{ __('sales.pos_receipt.thankyou') }}
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</div>

</body>
</html>
