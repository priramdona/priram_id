<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
    <style>
        /* Reset CSS */
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .email-header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 20px 0;
            font-size: 24px;
        }

        .email-body {
            padding: 20px;
            text-align: center;
        }

        .email-body p {
            font-size: 16px;
            color: #333;
        }

        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #4CAF50;
            margin: 20px 0;
        }

        .email-footer {
            background-color: #f4f4f4;
            color: #999;
            text-align: center;
            padding: 10px 0;
            font-size: 12px;
        }

        @media screen and (max-width: 600px) {
            .email-header, .email-body, .email-footer {
                padding: 10px;
            }

            .otp-code {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            Verifikasi OTP
        </div>
        <div class="email-body">
            <p>Halo {{ $user->name }},</p>
            <p>Berikut adalah kode OTP Anda:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p>Kode ini akan kedaluwarsa dalam 5 menit. Jangan berikan kode ini kepada siapa pun.</p>
        </div>
        <div class="email-footer">
            © {{ date('Y') }} Perusahaan Anda. Semua Hak Dilindungi.
        </div>
    </div>
</body>
</html>
