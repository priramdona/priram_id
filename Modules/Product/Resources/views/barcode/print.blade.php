{{-- <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barcodes</title>
    <link rel="stylesheet" href="{{ public_path('b3/bootstrap.min.css') }}">
</head>
<body>
<div class="container">
    <div class="row">
        @foreach($barcodes as $barcode)
            <div class="col-xs-3" style="border: 1px solid #dddddd;border-style: dashed;">
                <p style="font-size: 15px;color: #000;margin-top: 15px;margin-bottom: 5px;">
                    {{ $name }}
                </p>
                <div>
                    {!! $barcode !!}
                </div>
                <p style="font-size: 15px;color: #000;font-weight: bold;">
                    Price:: {{ format_currency($price) }}</p>
            </div>
        @endforeach
    </div>
</div>
</body>
</html> --}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barcodes</title>
    <link rel="stylesheet" href="{{ public_path('b3/bootstrap.min.css') }}">
    <style>
        /* Mengatur ukuran halaman A4 */
        @page {
            size: A4;
            margin: 0;
        }
        /* Mengatur seluruh body agar full page tanpa margin */
        body {
            margin: 0;
            padding: 0;
            width: 210mm;
            height: 297mm;
        }
        /* Mengatur ukuran setiap item barcode */
        .barcode-item {
            width: 66mm;
            height: 40mm;
            border: 1px dashed #dddddd;
            margin: 5px;
            padding: 10px;
            text-align: center;
            background-color: #48FCFE;
        }
        /* Mengatur teks di dalam barcode */
        .barcode-item p {
            font-size: 15px;
            color: #000;
        }
        .barcode-item .price {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        @foreach($barcodes as $barcode)
            <div class="barcode-item col-xs-3">
                <p style="margin-top: 5px; margin-bottom: 5px;">
                    {{ $name }}
                </p>
                <div>
                    {!! $barcode !!}
                </div>
                <p class="price">
                    Price: {{ format_currency($price) }}
                </p>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
