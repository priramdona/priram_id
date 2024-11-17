<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Atur ulang kata sandi | {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <!-- CoreUI CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
          integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
          crossorigin="anonymous"/>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body class="c-app flex-row align-items-center">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mx-4">
                <div class="card-body p-4">
                    <form method="post" action="{{ url('/password/reset') }}">
                        @csrf
                        <h1>Atur Ulang Kata Sandi</h1>
                        <p class="text-muted">Masukkan kata sandi baru</p>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                  </span>
                            </div>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                   value="{{ $email ?? old('email') }}" placeholder="Email" readonly>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                            </div>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" id="password" placeholder="Kata sandi">
                                   <span class="input-group-text" onclick="togglePassword('password', this)">
                                    <i class="bi bi-eye"></i>
                                </span>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                                </span>
                            </div>
                            <input type="password" class="form-control"
                                    name="password_confirmation" id="password_confirmation"
                                   placeholder="Konfirmasi kata sandi">
                                   <span class="input-group-text" onclick="togglePassword('password_confirmation', this)">
                                    <i class="bi bi-eye"></i>
                                    </span>
                        </div>

                        <button type="submit" class="btn btn-block btn-primary btn-block btn-flat">
                            <i class="fa fa-btn fa-refresh"></i> Atur ulang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CoreUI -->
<script src="{{ mix('js/app.js') }}" defer></script>
<script>

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
