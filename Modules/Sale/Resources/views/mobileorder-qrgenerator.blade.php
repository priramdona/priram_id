<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') || {{ config('app.name') }}</title>
    <meta content="Self Orders" name="author">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">

    @include('includes.main-css')

    <!-- Tambahkan styling untuk garis pembatas -->
    <style>
        figure {
            display: inline-block;
            text-align: center;
            margin-bottom: 20px;
        }

        figure img {
            max-width: 90%;
            height: auto;
            border-radius: 10px;
        }

        figure .figure-caption {
            font-size: 1rem;
            color: #6c757d;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .text-muted {
            font-size: 1rem;
        }

        .font-weight-bold {
            font-weight: bold;
            color: red;
        }
        .step-guide {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .step-item img {
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        .step-item img:hover {
            transform: scale(1.05);
        }
    </style>

    <!-- Masukkan QuaggaJS dari CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
  <!-- v2.0.0 -->
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/bootstrap/css/bootstrap.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/modules/core/common/core.cleanui.css') !!}">

</head>
<body class="c-app">
    <div class="c-wrapper">
        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid mb-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 text-center">
                            <!-- QR Code Section -->
                            <div class="card">
                                <div class="card-body">

                                    <div class="text-center mt-5">
                                        <h3 class="card-title mb-4">{{ $selforderBusiness->subject }}</h3>
                                        <p class="text-muted">{{ $selforderBusiness->captions }}</p>

                                    <!-- QR Code Image -->
                                        <figure>
                                            {{-- <img id="qrCodeImage" src="data:image/png;base64, {{ $qrCode }}" alt="QR Code" class="img-fluid img-thumbnail" style="width: 300px; height: 300px;"> --}}

                                            <img src="data:image/png;base64, {{ $qrCode }}" alt="QR Code" class="img-fluid img-thumbnail" style="width: 200px; height: 200px;">
                                            <figcaption class="figure-caption text-muted mt-2">
                                                {{ $businessData->name }}
                                            </figcaption>
                                        </figure>
                                    </div>

                                    <div class="step-guide mt-4 d-flex flex-wrap justify-content-around">
                                        <!-- Langkah 1 -->
                                        <div class="step-item text-center mx-2">
                                            {{-- <h5>1</h5> --}}
                                            <figure>
                                                <img src="{{ asset('images/mobileorder/mobileorder1.jpg') }}" alt="Step 1" class="img-fluid img-thumbnail" style="width: 100px; height: 100px;">
                                                <figcaption class="figure-caption text-muted mt-2">Langkah 1</figcaption>
                                            </figure>
                                        </div>

                                        <!-- Langkah 2 -->
                                        <div class="step-item text-center mx-2">
                                            {{-- <h5>2</h5> --}}
                                            <figure>
                                                <img src="{{ asset('images/mobileorder/mobileorder2.jpg') }}" alt="Step 2" class="img-fluid img-thumbnail" style="width: 100px; height: 100px;">
                                                <figcaption class="figure-caption text-muted mt-2">Langkah 2</figcaption>
                                            </figure>
                                        </div>

                                        <!-- Langkah 3 -->
                                        <div class="step-item text-center mx-2">
                                            {{-- <h5>3</h5> --}}
                                            <figure>
                                                <img src="{{ asset('images/mobileorder/mobileorder3.jpg') }}" alt="Step 3" class="img-fluid img-thumbnail" style="width: 100px; height: 100px;">
                                                <figcaption class="figure-caption text-muted mt-2">Langkah 3</figcaption>
                                            </figure>
                                        </div>

                                        <!-- Langkah 4 -->
                                        <div class="step-item text-center mx-2">
                                            {{-- <h5>4</h5> --}}
                                            <figure>
                                                <img src="{{ asset('images/mobileorder/mobileorder4.jpg') }}" alt="Step 4" class="img-fluid img-thumbnail" style="width: 100px; height: 100px;">
                                                <figcaption class="figure-caption text-muted mt-2">Langkah 4</figcaption>
                                            </figure>
                                        </div>

                                        <!-- Langkah 5 -->
                                        <div class="step-item text-center mx-2">
                                            {{-- <h5>5</h5> --}}
                                            <figure>
                                                <img src="{{ asset('images/mobileorder/mobileorder5.jpg') }}" alt="Step 5" class="img-fluid img-thumbnail" style="width: 100px; height: 100px;">
                                                <figcaption class="figure-caption text-muted mt-2">Langkah 5</figcaption>
                                            </figure>
                                        </div>

                                        <!-- Langkah 6 -->
                                        <div class="step-item text-center mx-2">
                                            {{-- <h5>6</h5> --}}
                                            <figure>
                                                <img src="{{ asset('images/mobileorder/mobileorder6.jpg') }}" alt="Step 6" class="img-fluid img-thumbnail" style="width: 100px; height: 100px;">
                                                <figcaption class="figure-caption text-muted mt-2">Langkah 6</figcaption>
                                            </figure>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        @include('layouts.footer')
    </div>
@include('includes.main-js')
</body>
</html>

