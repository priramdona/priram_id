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
                    <form id="entermobileorder" method="post" action="{{ route('selforder.posmobileorder', ['id' => $business]) }}">
                        @csrf
                        <h1>{{ __('selforder.enter.welcome') }}</h1>
                        <p class="text-muted">{{ __('selforder.enter.info_welcome') }}</p>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="width: 120px;margin-left: 5px;">
                                    {{ __('selforder.enter.mobile.first_name') }}
                                </span>
                            </div>
                            <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror"
                                   name="first_name" value="{{ old('first_name') }}"
                                   placeholder="{{ __('selforder.enter.mobile.first_name') }}">
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 120px;margin-left: 5px;">
                                        {{ __('selforder.enter.mobile.last_name') }}
                                    </span>
                            </div>
                            <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror"
                                   name="last_name" value="{{ old('last_name') }}"
                                   placeholder="{{ __('selforder.enter.mobile.last_name') }}">
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 120px;margin-left: 5px;">
                                        {{ __('selforder.enter.mobile.gender') }}
                                    </span>
                            </div>
                            <select name="gender" id="gender" class="form-control" required>

                                <option value="" selected disabled>{{ __('selforder.enter.mobile.gender_select') }}</option>
                                <option value="MALE">{{ __('selforder.enter.mobile.gender_male') }}</option>
                                <option value="FEMALE">{{ __('selforder.enter.mobile.gender_female') }}</option>

                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 120px;margin-left: 5px;">
                                        {{ __('selforder.enter.mobile.phone') }}
                                    </span>
                            </div>
                            <input id="phone_number" type="number" class="form-control @error('phone_number') is-invalid @enderror"
                                   name="phone_number" value="{{ old('phone_number') }}"
                                   placeholder="{{ __('selforder.enter.mobile.phone') }}">
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 120px;margin-left: 5px;">
                                        {{ __('selforder.enter.mobile.email') }}
                                    </span>
                            </div>
                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}"
                                   placeholder="{{ __('selforder.enter.mobile.email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <button id="submit" class="btn btn-primary px-4 d-flex align-items-center"
                                        type="submit">
                                        {{ __('selforder.enter.button_enter') }}
                                    <div id="spinner" class="spinner-border text-info" role="status"
                                         style="height: 20px;width: 20px;margin-left: 5px;display: none;">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </form>
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
