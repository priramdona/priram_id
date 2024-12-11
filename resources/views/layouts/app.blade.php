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
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
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

        .notification-item.bg-light {
            border: 1px solid #3a3a3a8f;
            border-left: 5px solid #1676dc;

            /* font-weight: bold; */
        }
        .notification-item a {
            margin-left: 15px;
        }

        /* Mengubah warna latar belakang sidebar */
        /* Ganti dengan warna yang diinginkan */
        /* .c-sidebar {
            background-color: #007bff;
        } */

        /* Mengubah warna teks pada sidebar */
        /* Ganti dengan warna teks yang diinginkan */
        /* .c-sidebar .c-sidebar-nav li a {
            color: #ffffff;
        } */

        /* Mengubah warna ketika item sidebar dihover */
        /* Ganti dengan warna hover yang diinginkan */
        /* .c-sidebar .c-sidebar-nav li a:hover {
            background-color: #0056b3;
        } */
        /* Warna parent ketika dropdown dibuka */
        /* Warna background parent ketika terbuka */
         /* Warna teks parent */
        /* .c-sidebar-nav-dropdown.c-show > .c-sidebar-nav-link {
            background-color: #007bff;
            color: #ffffff;
        } */

        /* Warna background untuk child items */
        /* Warna background child ketika parent terbuka */
        /* .c-sidebar-nav-dropdown.c-show > .c-sidebar-nav-dropdown-items {
            background-color: #0056b3;
        } */

        /* Warna link dalam child items */
        /* Warna teks link di child */
        /* .c-sidebar-nav-dropdown.c-show > .c-sidebar-nav-dropdown-items .c-sidebar-nav-link {
            color: #ffffff;
        } */

        /* Warna hover untuk child items */
        /* Warna hover untuk child */
        /* .c-sidebar-nav-dropdown.c-show > .c-sidebar-nav-dropdown-items .c-sidebar-nav-link:hover {
            background-color: #004085;
            color: #ffffff;
        } */

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
                @include('includes.messages')
                @yield('content')
            </main>
        </div>

        @include('layouts.footer')
    </div>

    @include('includes.main-js')
</body>
</html>
