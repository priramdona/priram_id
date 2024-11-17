<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Login | {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <!-- CoreUI CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
     <!-- Tambahkan styling untuk garis pembatas -->
     <style>
              /* Untuk Chrome, Safari, Edge, dan Opera */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Untuk Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>

<body class="c-app flex-row align-items-center">
<div class="container">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-center">
            <img width="150" src="{{ asset('images/logo-dark.png') }}" alt="Logo">
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-5">
            @if(Session::has('account_deactivated'))
                <div class="alert alert-danger" role="alert">
                    {{ Session::get('account_deactivated') }}
                </div>
            @endif
            <div class="card p-4 border-0 shadow-sm">
                <div class="card-body">
                    <form id="login" method="post" action="{{ url('/login') }}">
                        @csrf
                        <h1>Login</h1>
                        <p class="text-muted">Masuk ke akun Anda</p>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                    <span class="input-group-text">
                                      <i class="bi bi-person"></i>
                                    </span>
                            </div>
                            <input id="phone_number" type="number" class="form-control @error('phone_number') is-invalid @enderror"
                                   name="phone_number" value="{{ old('phone_number') }}"
                                   placeholder="Nomor Telepon">
                            @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                    <span class="input-group-text">
                                      <i class="bi bi-lock"></i>
                                    </span>
                            </div>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Kata Sandi" name="password">
                                <span class="input-group-text" onclick="togglePassword('password', this)">
                                    <i class="bi bi-eye"></i>
                                </span>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">

                            <div class="col-4">
                                <button id="submit" class="btn btn-primary px-4 d-flex align-items-center"
                                        type="submit">
                                    Masuk
                                    <div id="spinner" class="spinner-border text-info" role="status"
                                         style="height: 20px;width: 20px;margin-left: 5px;display: none;">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </button>
                            </div>
                            <div class="col-8 text-right">
                                <a class="btn btn-link px-0" href="{{ route('register') }}">
                                    Daftar
                                </a>
                            </div>

                        </div>
                        <div class="row text-center align-items-center">
                            <div class="col-8 mx-auto">
                                <a class="btn btn-link px-0" href="{{ route('password.request') }}">
                                    Lupa Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <p class="text-center mt-4">
                Dengan masuk, Anda setuju dengan
                <a href="{{ route('terms.service') }}" target="_blank">Syarat & Ketentuan</a>
                dan
                <a href="{{ route('privacy.policy') }}" target="_blank">Kebijakan Privasi</a> kami.
            </p>

        </div>
    </div>
</div>

<!-- CoreUI -->
<script src="{{ mix('js/app.js') }}" defer></script>
<script>
    let login = document.getElementById('login');
    let submit = document.getElementById('submit');
    let phone_number = document.getElementById('phone_number');
    let password = document.getElementById('password');
    let spinner = document.getElementById('spinner')

    login.addEventListener('submit', (e) => {
        submit.disabled = true;
        phone_number.readonly = true;
        password.readonly = true;

        spinner.style.display = 'block';

        // login.submit();
    });

    setTimeout(() => {
        submit.disabled = false;
        phone_number.readonly = false;
        password.readonly = false;

        spinner.style.display = 'none';
    }, 3000);


    function togglePassword(fieldId, toggleIcon) {
        const passwordField = document.getElementById(fieldId);
        const icon = toggleIcon.querySelector('i');

        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordField.type = "password";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>

</body>
</html>
