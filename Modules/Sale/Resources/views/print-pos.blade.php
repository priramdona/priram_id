<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>

        body {
            margin: 0;
            padding: 0;
        }

        /* Jika ingin diatur untuk tampilan layar */
        .page {
            width: 100%;
            margin: 0 auto;
            border: 0px solid #ddd;
            /* padding: 10px; */
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

            body {
                width: 100%;
            }
            .page {
                page-break-after: avoid; /* Hindari jeda halaman */
                page-break-before: avoid;
                page-break-inside: avoid;
            }
        }

        @page {
            margin: 0; /* Menghapus margin halaman PDF */
        }
        body {
            margin: 0;
            padding: 0;
            width: 100%; /* Pastikan lebar penuh */
        }
        .content {
            width: 100%; /* Pastikan konten menggunakan lebar penuh */
        }
    </style>
    <script>
        // Ambil publicUrl dari Blade Laravel
        // var publicUrl = "{{ $publicUrl ?? '' }}";
        // var saleData = "{{ $sale ?? '' }}";
        // var saleDetailsData = "{{ $saleDetail ?? '' }}";
        // var barcodeUrl = "{{ $barcodeUrl ?? '' }}";

        // document.addEventListener('DOMContentLoaded', function () {
        //     if (publicUrl !== '' && saleData !== '' && saleDetailsData !== '' && barcodeUrl !== '')
        //     {
        //         if (window.AndroidInterface) {
        //             window.AndroidInterface.sendDataArrayAndTables(publicUrl,saleData,saleDetailsData,barcodeUrl); // Mengirim ke Android interface
        //         }else {
        //             alert("Android interface not available");
        //         }
        //     }
        // });
        var publicUrl = "{{ $publicUrl }}";
        var saleData = @json($sale);
        var saleDetailsData = @json($saleDetail);
        var business = "{{ $business }}";

        document.addEventListener('DOMContentLoaded', function () {
            if (publicUrl !== '' && saleData !== '' && saleDetailsData !== '' && business !== '') {
                if (window.AndroidInterface) {
                    window.AndroidInterface.sendDataArrayAndTables(
                        publicUrl,
                        JSON.stringify(saleData),
                        JSON.stringify(saleDetailsData),
                        business
                    ); // Mengirim ke Android interface
                } else {
                    window.print();
                }
            }
        });
        // Fungsi untuk mengirim URL PDF ke Android
        function sendLinkPdf() {
            if (typeof Android !== "undefined" && Android.printPage && publicUrl !== '') {
                Android.printPage(publicUrl); // Panggil fungsi printPage dari Android
            } else {
                console.log("Android interface not available");
            }
        }
    </script>
</head>
<body>

    <div style="width: 100%; text-align: left; font-size: 12px; ">
        <div id="receipt-data">
            <div class="centered">
                <h2 style="margin-bottom: 5px">{{ settings()->company_name }}</h2>

                <p style="font-size: 12px;line-height: 15px;margin-top: 0">
                    {{ settings()->company_email }}, {{ settings()->company_phone }}
                    <br>{{ settings()->company_address }}
                </p>
            </div>
        <p style="font-size: 12px;">
            {{ __('sales.pos_receipt.date') }}: {{ \Carbon\Carbon::parse($sale->date)->format('d M, Y') }}<br>
            {{ __('sales.pos_receipt.reference') }}: {{ $sale->reference }}<br>
            {{ __('sales.pos_receipt.customer_name') }}: {{ $sale->customer_name }}
        </p>
        <table style="width: 100%; text-align: left; font-size: 12px; ">
            <tbody>
            @foreach($sale->saleDetails as $saleDetail)
                <tr>
                    <td style="width: 70%; text-align: left; font-size: 12px;">
                        {{  substr($saleDetail->product->product_name, 0, 37) }}<br>
                        ({{ $saleDetail->quantity }} x {{ str_replace('Rp. ','',format_currency($saleDetail->price)) }})
                    </td>
                    <td style="width: 30%;text-align:right;vertical-align:bottom;font-size: 12px;">{{ format_currency($saleDetail->sub_total) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <table style="width: 100%; text-align: left; font-size: 12px; ">
            <tbody>
            @if($sale->tax_percentage > 0)
                <tr>
                    <th style="text-align:left;font-size: 10px;">{{ __('sales.pos_receipt.tax_label') }} ({{ $sale->tax_percentage }}%)</th>
                    <th style="text-align:right;font-size: 10px;">{{ format_currency($sale->tax_amount) }}</th>
                </tr>
            @endif
            @if($sale->discount_percentage > 0)
                <tr>
                    <th style="text-align:left;font-size: 10px;">{{ __('sales.pos_receipt.discount_label') }} ({{ $sale->discount_percentage }}%)</th>
                    <th style="text-align:right;font-size: 10px;">{{ format_currency($sale->discount_amount) }}</th>
                </tr>
            @endif
            @if($sale->shipping_amount > 0)
                <tr>
                    <th style="text-align:left;font-size: 10px;">{{ __('sales.pos_receipt.shipping_label') }}</th>
                    <th style="text-align:right;font-size: 10px;">{{ format_currency($sale->shipping_amount) }}</th>
                </tr>
            @endif
            <tr>
                <th style="text-align:left;font-size: 10px;">{{ __('sales.pos_receipt.total_label') }}</th>
                <th style="text-align:right;font-size: 10px;">{{ format_currency($sale->total_amount) }}</th>
            </tr>
            @if($sale->additional_paid_amount > 0)
            <tr >
                <th style="text-align:left;font-size: 10px">{{ __('sales.pos_receipt.additional_amount_label') }}</th>
                <th style="text-align:right;font-size: 10px">{{ format_currency($sale->additional_paid_amount) }}</th>
            </tr>
            @endif
            </tbody>
        </table>
        <table>
            <tbody>
                <tr style="background-color:#ddd;">
                    <td class="centered" style="font-size: 10px;">
                        {{ __('sales.pos_receipt.payment.paid_by') }}<br> {{ $sale->payment_method }}
                    </td>
                    <td class="centered" style="font-size: 10px;">
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
                        <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode" style="width: 150px; height: 150px;" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
