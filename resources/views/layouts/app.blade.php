<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') || {{ config('app.name') }}</title>
    <meta content="Munggi Priramdona" name="author">
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
        #interactive {
            position: relative;
            width: 80%;
            max-width: 640px;
            height: 100px;
            margin: 0 auto;
            border: 2px solid #000;
        }

        #video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Garis pembatas berbentuk persegi panjang di tengah layar */
        .scanner-laser {
            position: absolute;
            border: 2px solid red;
            width: 80%;
            height: 80%;
            top: 10%;
            left: 10%;
            z-index: 2;
            box-shadow: 0 0 10px red;
        }

        /* Styling hasil scanning */
        #scanned-result {
            font-size: 18px;
            color: green;
            text-align: center;
            margin-top: 20px;
        }
    </style>

    <!-- Masukkan QuaggaJS dari CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
  <!-- v2.0.0 -->
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/vendors/bootstrap/css/bootstrap.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/dist/modules/core/common/core.cleanui.css') !!}">

</head>

<body class="c-app">

    @include('layouts.sidebar')

    <div class="c-wrapper">
        <header class="c-header c-header-light c-header-fixed">
            @include('layouts.header')
            <div class="c-subheader justify-content-between px-3">
                @yield('breadcrumb')
            </div>
        </header>

        <div class="c-body">
            <main class="c-main">
                @yield('content')
            </main>
        </div>

        @include('layouts.footer')
    </div>

    @include('includes.main-js')
</body>
</html>
