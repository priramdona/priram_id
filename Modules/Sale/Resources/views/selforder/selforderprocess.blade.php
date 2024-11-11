@extends('layouts.app')

@section('title', 'POS')

@section('third_party_stylesheets')

@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('menu.home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('menu.selforder') }}</li>
    </ol>
@endsection

@section('content')
<div class="container-fluid mb-4">

        <div class="container">
            <div class="col-12">
                @include('utils.alerts')
            </div>

            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-body">
                        <figure class="text-center">
                            <div class="position-relative">
                                <div id="interactiveqr" class="viewport">
                                    <video id="videoqr" autoplay></video>
                                    <div class="scanner-laser-qr">{{ __('selforder.process.scan') }}</div>
                                </div>
                            </div>

                            <figcaption class="figure-caption text-muted mt-2">
                                {{ __('selforder.process.info') }}
                            </figcaption>
                        </figure>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection

@push('page_scripts')

<script>

    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi suara beep
        var beepSound = new Audio("{{ asset('sounds/beep.mp3') }}");
        var klikSound = new Audio("{{ asset('sounds/klik.mp3') }}");

        Quagga.init({
                inputStream : {
                    name : "Live",
                    type : "LiveStream",
                    target: document.querySelector('#interactiveqr'), // Elemen video
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
        // Event handler ketika barcode terdeteksi
        Quagga.onDetected(function(result) {
            var code = result.codeResult.code;
            let inputField = document.getElementById('productcode');
            if(inputField) {
                inputField.value = code;
                inputField.dispatchEvent(new Event('input'));

                // Mainkan suara beep setelah barcode terdeteksi
                klikSound.play();
                 // Tampilkan hasil scan dan langsung redirect
                document.getElementById('scanned-result').innerText = `Code Scanned: ${code}`;
                setTimeout(() => {
                    window.location.href = `{{ url('selfordercheckout') }}/${code}`;
                }, 500); // Delay singkat sebelum redirect
            }
        });


    });
</script>

@endpush
@push('page_css')
<style>
    #interactiveqr {
        /* position: relative;
        width: 80%;
        max-width: 40%;
        height: 40%;
        margin: 0 auto;
        border: 2px solid #000; */
        position: relative;
        width: 100%;
        max-width: 500px;
        height: auto;
        aspect-ratio: 1 / 1;
        background-color: #000;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    #videoqr {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Garis pembatas berbentuk persegi panjang di tengah layar */
    .scanner-laser-qr {
        position: absolute;
        border: 2px solid green;
        width: 80%;
        height: 80%;
        top: 10%;
        left: 10%;
        z-index: 2;
        box-shadow: 0 0 10px green;
    }

    /* Styling hasil scanning */
    #scanned-result {
        font-size: 18px;
        color: green;
        text-align: center;
        margin-top: 20px;
    }
</style>
@endpush
