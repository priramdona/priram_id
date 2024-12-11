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
                            <!-- Elemen untuk menampilkan pemindai QR -->
                            <div id="interactiveqr" class="viewport">
                                <!-- `html5-qrcode` akan mengisi area ini -->
                            </div>
                        </div>
                        <figcaption class="figure-caption text-muted mt-2">
                            {{ __('selforder.process.info') }}
                        </figcaption>
                    </figure>
                    <!-- Menampilkan hasil scanning QR code -->
                    <div id="scanned-result" class="text-center mt-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('page_scripts')
<script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
<script>

    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi suara beep
        var beepSound = new Audio("{{ asset('sounds/beep.mp3') }}");
        var klikSound = new Audio("{{ asset('sounds/klik.mp3') }}");

        // Inisialisasi pemindai QR code menggunakan html5-qrcode
        const html5QrCode = new Html5Qrcode("interactiveqr");

        html5QrCode.start(
            { facingMode: "environment" },
            {
                fps: 10,    // Frame per detik untuk pemindaian
                qrbox: { width: 250, height: 250 } // Ukuran kotak untuk pemindaian QR code
            },
            (decodedText, decodedResult) => {
                // QR code berhasil dipindai
                document.getElementById('scanned-result').innerText = `Code Scanned: ${decodedText}`;

                // Mainkan suara klik
                klikSound.play();

                // Tunggu beberapa saat sebelum redirect ke halaman yang diinginkan
                setTimeout(() => {
                    window.location.href = `${decodedText}`;
                }, 500); // Delay singkat sebelum redirect
            },
            (errorMessage) => {
                // Pesan kesalahan jika pemindaian gagal
                console.log(errorMessage);
            }
        ).catch(err => {
            console.log("Unable to start scanning", err);
        });



    });
</script>

@endpush
@push('page_css')
<style>
    #interactiveqr {
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

    /* Styling garis di sekitar pemindai */
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

{{-- @push('page_css')
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
@endpush --}}
