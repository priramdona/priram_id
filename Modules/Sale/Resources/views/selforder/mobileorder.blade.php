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

    {{-- @include('layouts.sidebar') --}}

    <div class="c-wrapper">


        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            @include('utils.alerts')
                        </div>

                        <div class="col-lg-7">
                            {{-- <livewire:search-product-sale/> --}}
                            <livewire:search-product-mobile-selforder :business="$business"/>

                        </div>
                        <div class="col-lg-5">
                            <livewire:mobile-order.checkout :cart-instance="$customers->id" :customers="$customers" :business="$business" :selforder-business="$selforderBusiness"/>

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

<script src="{{ asset('js/jquery-mask-money.js') }}"></script>
<script type="text/javascript">
        $(document).ready(function () {
            $("#payment_method").empty();
            window.addEventListener('dispatchBrowserEvent',function(event)  {

                $.ajax({
                url: "{{ url('/get-payment-method') }}/",
                method: "GET",
                data: {
                    'source': 'selforder',
                },
                dataType: 'json',
                success: function(data) {
                    if (data.length > 0) {
                        $("#payment_method").empty();
                        op = '<option value="" disabled="true" selected="true">-Select-</option>'
                        for (var i = 0; i < data.length; i++) {
                            op += '<option value="' + data[i].id + '">' + data[i]
                                .name + '</option>';
                        }
                        $("#payment_method").append(op);
                        $('#payment_method').attr('hidden', false);
                        $('#payment_method').attr('hidden', false);

                    } else {
                        $("#payment_method").empty();
                    }
                }
            });
                $('#checkoutModal').modal('show');
                $('body').css('pointer-events', 'none');

                var grandTotal = event.detail[0].grandTotal;
                $('#paid_amount').val(grandTotal);
                $('#total_amount').val(grandTotal);

            });

            window.addEventListener('dispatchBrowserAction',function(event)  {
                $('#applyAction').modal('show');
                $('body').css('pointer-events', 'none');
            });
        });

        function generatePhone(id, productName) {
            var phoneNumber = document.getElementById('phone_number_' + id).value;
            var generateButton = document.getElementById('generate_button_' + id);
            generateButton.classList.remove('btn-primary');
            generateButton.classList.add('btn-success');
            generateButton.innerHTML = '<i class="bi bi-check"></i> Applied';
            generateButton.disabled = true;

            checkAllGenerated();
        }
        function removeRow(id) {
            // Hapus baris berdasarkan ID row
            var row = document.getElementById('row_' + id);

            if (row) {
                row.remove();
            }

            // Periksa lagi apakah semua tombol Generate sudah di-disable
            checkAllGenerated();
        }
        function checkAllGenerated() {
            // Dapatkan semua tombol Generate
            var buttons = document.querySelectorAll('button[id^="generate_button_"]');
            var allGenerated = true;

            // Cek apakah semua tombol sudah di-disable
            buttons.forEach(function(button) {
                if (!button.disabled) {
                    allGenerated = false;
                }
            });

            // Tampilkan tombol Proceed jika semua tombol sudah di-disable
            var proceedButton = document.getElementById('proccedaction');
            if (allGenerated) {
                proceedButton.hidden = false;
            } else {
                proceedButton.hidden = true;
            }
        }

        $('#closeModalCheckout').on('click', function() {
            $('#checkoutModal').modal('hide');
            $('body').css('pointer-events', 'auto');
        });

        $('#closeModalAction').on('click', function() {
            $('#applyAction').modal('hide');
            $('body').css('pointer-events', 'auto');
        });

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            //tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? ',' : '';
                rupiah += separator + ribuan.join(',');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            rupiahDecial = rupiah + '.00'
            return prefix == undefined ? rupiahDecial : (rupiahDecial ? 'Rp. ' + rupiahDecial : '');
        }

</script>
