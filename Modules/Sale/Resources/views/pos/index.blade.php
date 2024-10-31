@extends('layouts.app')

@section('title', 'POS')

@section('third_party_stylesheets')

@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">POS</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @include('utils.alerts')
            </div>

            <div class="col-lg-7">
                <livewire:search-product-sale/>
                <livewire:pos.product-list :categories="$product_categories"/>
            </div>
            <div class="col-lg-5">
                <livewire:pos.checkout :cart-instance="'sale'" :customers="$customers"/>

            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script src="{{ asset('js/jquery-mask-money.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#payment_method").empty();
            window.addEventListener('dispatchBrowserEvent',function(event)  {
                $.ajax({
                url: "{{ url('/get-payment-method') }}/",
                method: "GET",
                data: {
                    'source': 'pos',
                },
                dataType: 'json',
                success: function(data) {
                    if (data.length > 0) {
                        // alert(data);
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
            // alert(phoneNumber + ' id = ' +  id);
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

@endpush
