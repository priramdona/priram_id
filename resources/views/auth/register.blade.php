<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Register | {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <!-- CoreUI CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body class="c-app flex-row align-items-center">
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7 col-sm-10">
            <div class="card mx-4 mt-4 shadow-sm">
                <div class="card-body p-4">
                    <form method="post" action="{{ url('/register') }}" onsubmit="return validateForm()">
                        @csrf
                        <h1 class="text-center mb-4">Pendaftaran</h1>
                        <p class="text-muted text-center">Informasi Pengguna & Usaha</p>

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Full Name">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Business Name -->
                        <div class="mb-3">
                            <label for="business_name" class="form-label">Nama Usaha</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                <input type="text" class="form-control @error('business_name') is-invalid @enderror" id="business_name" name="business_name" value="{{ old('business_name') }}" placeholder="Business Name">
                                @error('business_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Business Address -->
                        <div class="mb-3">
                            <label for="business_address" class="form-label">Alamat Usaha</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-map"></i></span>
                                <input type="text" class="form-control @error('business_address') is-invalid @enderror" id="business_address" name="business_address" value="{{ old('business_address') }}" placeholder="Business Address">
                                @error('business_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <p class="text-muted text-center">Informasi Akun</p>

                        <!-- Mobile Phone -->
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Nomor Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                <input type="number" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="Mobile Phone">
                                @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <span class="text-danger" style="font-size: 12px;">(Pastikan Email Aktif, karena akan dikirimkan OTP)</span>

                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password">
                                <span class="input-group-text" onclick="togglePassword('password', this)">
                                    <i class="bi bi-eye"></i>
                                </span>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                                <span class="input-group-text" onclick="togglePassword('password_confirmation', this)">
                                    <i class="bi bi-eye"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Terms and Conditions Checkbox -->
                        <div class="form-check d-flex align-items-center mb-4">
                            <input type="checkbox" id="terms" name="terms" class="form-check-input me-2">
                            <label class="form-check-label" for="terms">
                                Saya telah membaca dan setuju dengan

                                <a href="{{ route('terms.service') }}" target="_blank">Syarat Ketentuan</a>
                                &
                                <a href="{{ route('privacy.policy') }}" target="_blank">Kebijakan Privasi</a>.

                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-block mb-3">Daftar</button>
                        <p class="text-center">
                            <a href="{{ route('login') }}">Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CoreUI -->
<script src="{{ mix('js/app.js') }}" defer></script>

<script>
function validateForm() {
    const termsChecked = document.getElementById('terms').checked;
    if (!termsChecked) {
        alert("Please accept the Terms of Service and Privacy Policy to continue.");
        return false;
    }
    return true;
}

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
