@extends('layouts.app')

@section('title', __('home.title'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item active">{{ __('home.breadcrumb') }}</li>
    </ol>
@endsection

@section('content')
<div id="interactive" class="viewport">
    <video id="video" autoplay></video>
    <div class="scanner-laser"></div> <!-- Garis pembatas barcode -->
</div>

<!-- Hasil scanning -->
<p id="scanned-result">Scanned Code: <span id="scanned-code"></span></p>

@endsection

@section('third_party_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js"
            integrity="sha512-asxKqQghC1oBShyhiBwA+YgotaSYKxGP1rcSYTDrB0U6DxwlJjU59B67U8+5/++uFjcuVM8Hh5cokLjZlhm3Vg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@push('page_scripts')
    @vite('resources/js/chart-config.js')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi QuaggaJS untuk memulai kamera dan scanning
            Quagga.init({
                inputStream : {
                    name : "Live",
                    type : "LiveStream",
                    target: document.querySelector('#interactive'), // Elemen video
                    constraints: {
                        facingMode: "environment", // Menggunakan kamera belakang
                        advanced: [
                            { focusMode: "manual" }, // Nonaktifkan autofokus
                            { zoom: 4 },  // Perbesar tampilan untuk barcode kecil
                        ]
                    }
                },
                locator: {
                    patchSize: "small",  // Ukuran deteksi lebih kecil untuk barcode kecil
                    halfSample: false,    // Tidak perlu sampling 50% untuk akurasi yang lebih baik
                    debug: {
                        showCanvas: true, // Menampilkan canvas untuk debug
                        showPatches: true,
                        showFoundPatches: true,
                        showSkeleton: true,
                        showLabels: true,
                        showPatchLabels: true,
                        showRemainingPatchLabels: true,
                    }
                },
                area: { // Fokus deteksi hanya pada bagian tengah
                    top: "30%",    // 30% dari atas
                    right: "30%",  // 30% dari kanan
                    left: "30%",   // 30% dari kiri
                    bottom: "30%"  // 30% dari bawah
                },
                decoder : {
                    readers : ["code_128_reader", "ean_reader"],  // Jenis barcode yang ingin di-scan
                },
                locate: true // Aktifkan mode pelacakan barcode
            }, function(err) {
                if (err) {
                    console.log(err);
                    return;
                }
                console.log("Quagga initialized successfully");
                Quagga.start();
            });

            // Callback ketika barcode terdeteksi
            Quagga.onDetected(function(result) {
                var code = result.codeResult.code;
                document.getElementById('scanned-code').innerText = code;
                console.log("Barcode Detected: " + code);
            });
        });
    </script>
@endpush
