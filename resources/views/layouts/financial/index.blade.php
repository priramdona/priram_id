@extends('layouts.app')

@section('title', __('home.title'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item active">{{ __('payment_gateway.withdrawal') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="card border-0">
                    <div class="card-body p-0 d-flex align-items-center shadow-sm">
                        <div class="bg-gradient-primary p-4 mfe-3 rounded-left">
                            <i class="bi bi-cash font-2xl"></i>
                        </div>
                        <div>
                            <div class="text-value text-primary" style="font-weight: bold; font-size: 20px;">{{ format_currency($balance) }}</div>
                            <div class="text-muted text-uppercase font-weight-bold large">{{ __('payment_gateway.saldo') }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
        <div class="col-lg-12">
            @include('utils.alerts')
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('payment_gateway.withdraw_balance') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('financial.management.withdraw.process') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label name="label_transaction_amount" id="label_transaction_amount" for="transaction_amount">{{ __('payment_gateway.transaction_amount') }}<span class="text-danger"> {{ __('payment_gateway.minimum_amount') }}</span><span class="text-info"> ({{ __('payment_gateway.admin_fee') }})</span></label>

                                     <input onkeydown="if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') { event.preventDefault(); }"
                                    type="number" class="form-control" name="transaction_amount" id="transaction_amount" value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="disbursement_method">{{ __('payment_gateway.method') }}<span class="text-danger">*</span></label>
                                    <select name="disbursement_method" id="disbursement_method" class="form-control" required>
                                        <option value="" selected disabled>{{ __('payment_gateway.select') }}</option>
                                        @foreach(\Modules\PaymentGateway\Entities\XenditDisbursementMethod::where('status',true)->get() as $disbursement_method)
                                            <option value="{{ $disbursement_method->id }}">{{ $disbursement_method->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label name="label_disbursement_channel" id="label_disbursement_channel" for="disbursement_channel">{{ __('payment_gateway.bank') }}<span class="text-danger">*</span></label>
                                    <select name="disbursement_channel" id="disbursement_channel" class="form-control" required>
                                        <option value="" selected disabled>{{ __('payment_gateway.select_channel') }}</option>
                                        <!-- Options akan diisi berdasarkan pilihan disbursement_method -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="account_name">{{ __('payment_gateway.account_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="account_name" id="account_name" value="" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label name="label_account_number" id="label_account_number" for="account_number">{{ __('payment_gateway.account_number') }} <span class="text-danger">*</span></label>
                                    <input onkeydown="if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') { event.preventDefault(); }"
                                    type="number" class="form-control" name="account_number" id="account_number" value="" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label name="label_transaction_info" id="label_transaction_info" for="transaction_info">{{ __('payment_gateway.requested_amount') }} : </label>
                                    <label class="text-info font-weight-bold col-lg-6" id="transaction_info" name="transaction_info" style="font-weight: bold; font-size: 20px;">Rp. 0.00</label>

                                    <label name="label_amount" id="label_amount" for="amount">{{ __('payment_gateway.deduction_amount') }} : </label>
                                    <label class="text-primary font-weight-bold col-lg-6" id="amount_info" name="amount_info" style="font-weight: bold; font-size: 20px;">Rp. 0.00</label>

                                    <input type="hidden" class="form-control" name="amount" id="amount" value="" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="company_address">{{ __('payment_gateway.notes') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="notes" id="notes" value="">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> {{ __('payment_gateway.withdraw') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('third_party_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js"
            integrity="sha512-asxKqQghC1oBShyhiBwA+YgotaSYKxGP1rcSYTDrB0U6DxwlJjU59B67U8+5/++uFjcuVM8Hh5cokLjZlhm3Vg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@push('page_scripts')

    <script>
        // $(document).ready(function () {
            $('#disbursement_method').on('change', function () {

                var xdmId = document.getElementById('disbursement_method').value;
                var xdmName = $('#disbursement_method option:selected').text();
                var income_amount = parseFloat(document.getElementById('transaction_amount').value) || 0;
                var net_amount = income_amount + 3000;
                if (income_amount >= 10000){
                    $('input[name=amount]').val(net_amount);
                    var infoamount = document.getElementById('amount').value;
                    var infotransaction = document.getElementById('transaction_amount').value;
                    $('#amount_info').text(formatRupiah(infoamount,'Rp. '));
                    $('#transaction_info').text(formatRupiah(infotransaction,'Rp. '));
                }else{
                    $('input[name=amount]').val(0);
                    var infoamount = document.getElementById('amount').value;
                    $('#amount_info').text(formatRupiah(infoamount,'Rp. '));
                    $('#transaction_info').text(formatRupiah(0,'Rp. '));
                }

                $('#label_disbursement_channel').text(xdmName);

                // Hapus semua opsi sebelumnya dari dropdown disbursement_channel
                $('#disbursement_channel').empty().append('<option value="" selected disabled>{{ __('payment_gateway.select_channel') }}</option>');

                if (xdmId) {
                    // Kirim permintaan AJAX ke server untuk mendapatkan channel berdasarkan xdm_id
                    $.ajax({
                        url: '/get-disbursement-channels', // Endpoint untuk mengambil channel berdasarkan xdm_id
                        type: 'GET',
                        data: { xdm_id: xdmId },
                        success: function (channels) {
                            // Loop melalui hasil dan tambahkan ke dropdown disbursement_channel
                            $.each(channels, function (index, channel) {
                                $('#disbursement_channel').append(`<option value="${channel.id}">${channel.name}</option>`);
                            });
                        },
                        error: function () {
                            alert('Error.. Please try again..');
                        }
                    });
                }
            });
        // });
        $(document).on('input', '#transaction_amount', function() {
            var income_amount = parseFloat(document.getElementById('transaction_amount').value) || 0;
            var net_amount = income_amount + 3000;
            if (income_amount >= 10000){
                $('input[name=amount]').val(net_amount);
                var infoamount = document.getElementById('amount').value;
                var infotransaction = document.getElementById('transaction_amount').value;
                $('#amount_info').text(formatRupiah(infoamount,'Rp. '));
                $('#transaction_info').text(formatRupiah(infotransaction,'Rp. '));
            }else{
                $('input[name=amount]').val(0);
                var infoamount = document.getElementById('amount').value;
                $('#amount_info').text(formatRupiah(infoamount,'Rp. '));
            }
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
