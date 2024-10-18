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
                $('body').css('pointer-events', 'none'); // Menonaktifkan semua interaksi

                // $('#paid_amount').maskMoney({
                //     prefix:'{{ settings()->currency->symbol }}',
                //     thousands:'{{ settings()->currency->thousand_separator }}',
                //     decimal:'{{ settings()->currency->decimal_separator }}',
                //     allowZero: false,
                // });

                // $('#total_amount').maskMoney({
                //     prefix:'{{ settings()->currency->symbol }}',
                //     thousands:'{{ settings()->currency->thousand_separator }}',
                //     decimal:'{{ settings()->currency->decimal_separator }}',
                //     allowZero: true,
                // });

                // $('#paid_amount').maskMoney('mask');
                // $('#total_amount').maskMoney('mask');

                // $('#checkout-form').submit(function () {
                //     var paid_amount = $('#paid_amount').maskMoney('unmasked')[0];
                //     $('#paid_amount').val(paid_amount);
                //     var total_amount = $('#total_amount').maskMoney('unmasked')[0];
                //     $('#total_amount').val(total_amount);
                // });

                // var sale_amount = document.getElementById('amount_sale').value;
                var grandTotal = event.detail[0].grandTotal;
                $('#paid_amount').val(grandTotal);
                $('#total_amount').val(grandTotal);

            });
        });


        $('#closeModalCheckout').on('click', function() {
            $('#checkoutModal').modal('hide');
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
