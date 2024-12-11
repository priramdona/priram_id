<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Pelayanan Mandiri</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <!-- CoreUI CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

<!-- CoreUI -->
<script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Masukkan QuaggaJS dari CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
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
</head>

<body class="c-app flex-row align-items-center">
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card p-4 border-0 shadow-sm">
                <div class="card-body">
                    <figure class="text-center">
                        <div class="position-relative">
                            <!-- Elemen untuk menampilkan pemindai QR -->
                            <div id="interactiveqr" class="viewport">
                                <!-- `html5-qrcode` akan mengisi area ini -->
                            </div>
                        </div>
                        <figcaption class="figure-caption text-muted mt-2">
                            Silakan Pindai QR Code untuk memulai Belanja
                        </figcaption>
                    </figure>
                    <!-- Menampilkan hasil scanning QR code -->
                    <div id="scanned-result" class="text-center mt-2"></div>
                </div>
            </div>

        </div>
    </div>
</div>


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

</body>
</html>
