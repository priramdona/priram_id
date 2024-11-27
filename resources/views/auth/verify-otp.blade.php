<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Verifikasi OTP | {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">

    <!-- CoreUI CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" crossorigin="anonymous">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f4f4f4;
        }

        .card {
            border-radius: 15px;
        }

        .card-header {
            background-color: #4CAF50;
            color: white;
            font-size: 24px;
            font-weight: bold;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .otp-input-wrapper {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border: 2px solid #ddd;
            border-radius: 8px;
            transition: border-color 0.3s ease;
        }

        .otp-input:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .btn-outline-primary {
            color: #4CAF50;
            border-color: #4CAF50;
        }

        .btn-outline-primary:hover {
            background-color: #4CAF50;
            color: white;
        }

        #countdown {
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>

<body class="c-app flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        Verifikasi OTP
                    </div>
                    <div class="card-body">
                        <p class="text-center">Masukkan kode OTP yang telah dikirim ke email Anda.</p>
                        <form method="POST" action="{{ route('otp.verify') }}">
                            @csrf
                            <div class="otp-input-wrapper">
                                <input type="text" id="otp1" name="otp[]" maxlength="1" class="otp-input" required autofocus>
                                <input type="text" id="otp2" name="otp[]" maxlength="1" class="otp-input" required>
                                <input type="text" id="otp3" name="otp[]" maxlength="1" class="otp-input" required>
                                <input type="text" id="otp4" name="otp[]" maxlength="1" class="otp-input" required>
                                <input type="text" id="otp5" name="otp[]" maxlength="1" class="otp-input" required>
                                <input type="text" id="otp6" name="otp[]" maxlength="1" class="otp-input" required>
                            </div>

                            @error('otp')
                                <span class="text-danger d-block text-center">{{ $message }}</span>
                            @enderror

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Verifikasi</button>
                            </div>
                        </form>

                        <div class="mt-3 text-center">
                            <span id="countdown" class="text-muted"></span>
                        </div>

                        <div class="mt-3 text-center">
                            <button id="sendOtpButton" class="btn btn-outline-primary">Kirim Ulang OTP</button>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- CoreUI -->
<script src="{{ mix('js/app.js') }}" defer></script>

<script>
    // Otomatisasi fokus input OTP
    const otpInputs = document.querySelectorAll('.otp-input');
    var remainingtime = 0;
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length > 0) {
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            } else {
                if (index > 0) {
                    otpInputs[index - 1].focus();
                }
            }
        });
    });

    document.getElementById('sendOtpButton').addEventListener('click', function () {
    fetch("{{ route('otp.send') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        }
    })
    .then(response => {
        if (!response.ok) {
            // Jika status tidak 200
            return response.json().then(data => {
                throw new Error(data.message || 'Gagal mengirim email OTP.');
            });
        }
        return response.json(); // Jika status 200
    })
    .then(data => {
        // Swal success jika berhasil
        Swal.fire({
            title: 'Sukses!',
            text: data.message || 'Email OTP berhasil dikirim.',
            icon: 'success',
            confirmButtonText: 'OK',
            timer: 3000, // Menutup otomatis dalam 3 detik
            timerProgressBar: true
        });

        // Memulai countdown dari waktu yang tersisa atau default 60 detik
        startCountdown(data.remaining_time ?? 60);

    }).catch(error => {
        // Swal error jika gagal
        Swal.fire({
            title: 'Gagal!',
            text: error.message || 'Terjadi kesalahan saat mengirim email OTP.',
            icon: 'error',
            confirmButtonText: 'Coba Lagi'
        });
    });
});

    // Hitungan mundur pengiriman ulang OTP
    function startCountdown(seconds) {
        let countdownElement = document.getElementById('countdown');
        countdownElement.textContent = `Tunggu ${seconds} detik untuk kirim ulang.`;

        const interval = setInterval(() => {
            seconds--;
            countdownElement.textContent = `Tunggu ${seconds} detik untuk kirim ulang.`;

            if (seconds <= 0) {
                clearInterval(interval);
                countdownElement.textContent = '';
            }
        }, 1000);
    }
</script>

</body>
</html>
