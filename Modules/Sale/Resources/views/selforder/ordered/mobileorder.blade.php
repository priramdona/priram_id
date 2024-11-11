<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Mobile Self Order</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <!-- CoreUI CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>

<body class="c-app flex-row align-items-center">
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card p-4 border-0 shadow-sm">
                <div class="card-body">
                    <h1>Terima Kasih!</h1>
                    <div class="text-center mt-5">
                        <p class="text-muted">{{ $link }}</p>
                    <!-- QR Code Image -->
                        <figure>
                            {{-- <img id="qrCodeImage" src="data:image/png;base64, {{ $qrCode }}" alt="QR Code" class="img-fluid img-thumbnail" style="width: 300px; height: 300px;"> --}}

                            <img src="data:image/png;base64, {{ $qrCode }}" alt="QR Code" class="img-fluid img-thumbnail" style="width: 200px; height: 200px;">
                            <figcaption class="figure-caption text-muted mt-2">

                            </figcaption>
                        </figure>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- CoreUI -->
<script src="{{ mix('js/app.js') }}" defer></script>
<script>
    let entermobileorder = document.getElementById('entermobileorder');
    let first_name = document.getElementById('first_name');
    let last_name = document.getElementById('last_name');
    let phone_number = document.getElementById('phone_number');
    let email = document.getElementById('email');
    let gender = document.getElementById('gender');
    let spinner = document.getElementById('spinner')

    entermobileorder.addEventListener('submit', (e) => {
        submit.disabled = true;
        first_name.readonly = true;
        last_name.readonly = true;
        phone_number.readonly = true;
        email.readonly = true;
        gender.readonly = true;
        spinner.style.display = 'block';
        // entermobileorder.submit();
    });

    setTimeout(() => {
        submit.disabled = false;
        first_name.readonly = false;
        last_name.readonly = false;
        phone_number.readonly = false;
        email.readonly = false;
        gender.readonly = false;

        spinner.style.display = 'none';
    }, 3000);
</script>

</body>
</html>
