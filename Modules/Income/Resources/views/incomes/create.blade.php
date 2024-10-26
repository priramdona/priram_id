@extends('layouts.app')

@section('title', 'Create Income')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('incomes.index') }}">Incomes</a></li>
        <li class="breadcrumb-item active">Add</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <form id="income-form" action="{{ route('incomes.store') }}" method="POST">
            @csrf
            <div class="row">

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">

                                        <input type="hidden" id="payment_id" name="payment_id">
                                        <label for="reference">Reference <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="reference" required readonly value="INC">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="date">Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="date" required value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="category_id">Category <span class="text-danger">*</span></label>
                                        <select name="category_id" id="category_id" class="form-control" required>
                                            <option value="" selected>Select Category</option>
                                            @foreach(\Modules\Income\Entities\IncomeCategory::where('business_id',auth::user()->business_id)->get() as $category)
                                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="amount">Amount <span class="text-danger">*</span></label>

                                        <label class="text-primary font-weight-bold col-lg-6" id="lbl_amount" name="lbl_amount">Rp. 0.00</label>
                                        <input id="amount" type="amount" class="form-control" name="amount" value="0" required>

                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="details">Details</label>
                                        <textarea class="form-control" rows="6" name="details"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <tr class="text-success">
                                                <th>Payment Fee</th>
                                                <input name="payment_fee" id="payment_fee" type="hidden" value="0">
                                                <td id="payment_fee_info">Rp. 0.00</td>
                                            </tr>
                                            <tr class="text-success">
                                                <th>PPN <span class="small ">(From Payment Fee)</span></th>
                                                <input name="payment_ppn" id="payment_ppn" type="hidden" value="0">
                                                <td id="payment_ppn_info">Rp. 0.00</td>
                                            </tr>
                                            <tr class="text-success">
                                                <th>Application Fee</th>
                                                <input name="application_fee" id="application_fee" type="hidden" value="0">
                                                <td id="application_fee_info">Rp. 0.00</td>
                                            </tr>
                                            <tr class="text-primary font-weight-bold">
                                                <th>Grand Total</th>
                                                <input name="grand_total" id="grand_total" type="hidden">
                                                <th id="grand_total_info"></th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                                        <select class="form-control paymentmethod" name="payment_method" id="payment_method" required>

                                        </select>

                                        <label for="payment_channel" name="lbl_payment_channel" id="lbl_payment_channel" hidden>Payment Channel <span class="text-danger">*</span></label>
                                        <select class="form-control" name="payment_channel" id="payment_channel" onchange="fetchdetailchannel()"  hidden>
                                            <option value="">-Select-</option>
                                        </select>
                                        <label for="number_phone" name="lbl_number_phone" id="lbl_number_phone"  hidden>OVO Phone Number<span class="text-danger"> *</span></label>
                                        <div  class="col-11 row" name="group_number_phone" id="group_number_phone">
                                            <label class="col-2 text-small align-right"  hidden>+62</label>
                                            <input type="text" name="number_phone" id="number_phone" class="form-control form-control-sm col-9" oninput="this.value = this.value.replace(/[^0-9]/g, '');"  hidden>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    @include('utils.alerts')
                                    <div class="form-group">
                                        <button class="btn btn-primary" id='submitBtn' name='submitBtn' hidden>Create Income <i class="bi bi-check"></i></button>
                                        <button id='continuePayment' name='continuePayment' type="button" class="btn btn-success" hidden>Process to Payment</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="actionModal" tabindex="-1" role="dialog" aria-labelledby="lbl_payment_action" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lbl_payment_action"></h5>
                </div>
                <div class="modal-body">
                    <div class="form-group align-middle text-center" name="payment_action" id="payment_action" hidden>
                        <div class="get-barcode" name="action_account_barcode" id="action_account_barcode">
                            <div id="qr-code-container" style="display: flex; justify-content: center; align-items: center; height: 100%;"></div>
                        </div>
                        <div  name="action_account_account" id="action_account_account" hidden>

                            <label class="text-primary" style="font-weight: bold; font-size: 24px;" name="input_payment_action_account" id="input_payment_action_account">123456789</label>
                            <p id="name_action_account"></p>
                            <p id="inc_action_account"></p>
                        </div>
                        <div  name="action_account_info" id="action_account_info" hidden>
                            <label class="text-primary" style="font-weight: bold; font-size: 18px;" name="input_payment_action_info" id="input_payment_action_info">123456789</label>
                        </div>
                        <div  name="action_account_url" id="action_account_url" hidden>
                            <iframe id="urlIframe" width="100%" height="450px" frameborder="0"></iframe>
                        </div>

                        <label  name="lbl_payment_by" id="lbl_payment_by" for="payment_by">Payment by : </span></label>
                        <img  name="payment_by" id="payment_by" src="" style="width: 100px; height: 50px;"/>
                        <div><label class="text-success" style="font-weight: bold; font-size: 24px;" name="nominal_information" id="nominal_information">123456789</label>
                            </div>

                    </div>

                    <p id="lbl_payment_information">If <b>Succeed</b> the amount will automatically add to your <i class="bi bi-cash text-primary"></i><span style="background-color: yellow;"><b>"[Balance]"</b></span>.<br> after settlement process. Please makesure the payment process is successfull.</p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" name="manualConfirmation" id="manualConfirmation" onclick="window.location='{{ route('incomes.index') }}'" >New Transaction</button>
                    {{-- <button type="button" class="btn btn-warning" name="setToWaiting" id="setToWaiting" hidden>Set to Waiting status</button> --}}
                 </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script src="{{ asset('js/jquery-mask-money.js') }}"></script>
    <script>

     var startautosave;

        $(document).ready(function () {
            $.ajax({
                url: "{{ url('/get-payment-method') }}/",
                method: "GET",
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
            // $('#amount').maskMoney({
            //     prefix:'{{ settings()->currency->symbol }}',
            //     thousands:'{{ settings()->currency->thousand_separator }}',
            //     decimal:'{{ settings()->currency->decimal_separator }}',
            // });

            $('#income-form').on('submit', function(e) {

                var paymentChannel = document.getElementById('payment_channel').value;
                if (paymentChannel.length > 0){
                    e.preventDefault();
                }
                // var paid_amount = $('#paid_amount').maskMoney('unmasked')[0];
                // $('#paid_amount').val(paid_amount);
                // var total_amount = $('#total_amount').maskMoney('unmasked')[0];
                // $('#total_amount').val(total_amount);
                var formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                });
            });

            var income_amount = document.getElementById('amount').value;
            $('#lbl_amount').text(formatRupiah(income_amount,'Rp. '));

        });

        $(document).on('input', '#amount', function() {
            var income_amount = document.getElementById('amount').value;

            $('#lbl_amount').text(formatRupiah(income_amount,'Rp. '));
            fetchPaymentChannels();
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

        $(document).on('change', '#payment_method', function() {

            var income_amount = document.getElementById('amount').value;

            // var amount = $('#amount').maskMoney('unmasked')[0];
            if (income_amount > 0){

                fetchPaymentChannels();
            }else{
                Swal.fire({
                    title: 'Error Payment Method',
                    text: 'Please put Amount first!',
                    icon: 'error',
                    didOpen: () => {
                            $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                    },
                });

            }
        });



        $(document).on('click', '#continuePayment', function() {
        var paymentChannel = document.getElementById('payment_channel').value;
        var numberPhone = document.getElementById('number_phone').value;

        var selectedOptionText = $('#payment_channel option:selected').text();
        var amount =  document.getElementById('grand_total').value; //$('#paid_amount').maskMoney('unmasked')[0];
        var income_amount = document.getElementById('amount').value;// $('#amount_sale').maskMoney('unmasked')[0];;

        if (selectedOptionText == 'OVO'){

            if (numberPhone.length < 8) {
                Swal.fire({
                    title: 'Error Phone Number',
                    text: 'Please Check Phone Number first!',
                    icon: 'error',
                    didOpen: () => {
                            $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                    },
                });

                $('#number_phone').focus();
                return;
            }
        }

        // $('#manualConfirmation').attr('hidden', true);
        // $('#setToWaiting').attr('hidden', true);
        $('#input_payment_action_barcode').attr('hidden', true);
        $('#action_account_account').attr('hidden', true);
        $('#action_account_barcode').attr('hidden', true);
        $('#payment_by').attr('hidden', true);
        $('#lbl_payment_by').attr('hidden', true);

        Swal.fire({
                title: 'Payment Confirmation',
                text: "Do you want to proceed this payment?",
                icon: 'question',  // Tipe ikon question
                showCancelButton: true,  // Menampilkan tombol cancel
                confirmButtonColor: '#3085d6',  // Warna tombol confirm
                cancelButtonColor: '#d33',  // Warna tombol cancel
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel',
                didOpen: () => {
                        $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                },
                }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                title: 'Disclaimer Payment Information',
                // text: "balance.. .",
                icon: 'info',  // Tipe ikon question
                html: 'This payment cannot be Cancel and will add to your subaccount..',
                showCancelButton: true,  // Menampilkan tombol cancel
                confirmButtonColor: '#3085d6',  // Warna tombol confirm
                cancelButtonColor: '#d33',  // Warna tombol cancel
                confirmButtonText: 'Yes, go ahead!',
                cancelButtonText: 'No, cancel!',
                didOpen: () => {
                    $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                },
                        }).then((result) => {
                            if (result.isConfirmed) {
                                    Swal.fire({
                                    title: 'Processing...',
                                    text: 'Please wait while your request is being processed.',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                    });
                                        $.ajax({
                                            url: "{{ url('/get-payment') }}/",
                                            method: "GET",
                                            data: {
                                                'payment_channel_id': paymentChannel,
                                                'amount': amount,
                                                'sale_amount': income_amount,
                                                'number_phone': numberPhone,
                                                'transaction_type': 'income',
                                                // 'action': data.action,
                                                // 'source': data.source
                                            },
                                            dataType: 'json',
                                            success: function(response) {
                                                Swal.fire({
                                                    title: 'Payment Process',
                                                    text: 'Your Payment has been generated..',
                                                    icon: 'success',
                                                    didOpen: () => {
                                                            $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                                                    },
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            $('#nominal_information').text(response.nominal_information);
                                                            $('#actionModal').modal('show');
                                                            $('#checkoutModal').modal('hide');
                                                            // $('#manualConfirmation').attr('hidden', false);
                                                            // $('#setToWaiting').attr('hidden', false);
                                                            $('#payment_action').attr('hidden', false);
                                                            $('#payment_by').attr('hidden', false);
                                                            $('#lbl_payment_by').attr('hidden', false);

                                                            $.ajax({
                                                            url: "{{ url('/get-payment-channel-details') }}/",
                                                            method: "GET",
                                                            data: {
                                                                'id': paymentChannel,
                                                            },
                                                            dataType: 'json',
                                                            success: function(data) {

                                                                $('#payment_by').attr('src', data.image_url);
                                                                $('input[name=payment_id]').val(response.payment_request_id);

                                                                if (response.response_type == 'qrcode') {
                                                                    $('#lbl_payment_action').text('Please Scan Barcode to Process payment');
                                                                    $('#qr-code-container').html(response.value_response);
                                                                    $('#input_payment_action_account').text("");
                                                                    $('#action_account_barcode').attr('hidden', false);
                                                                    $('#action_account_account').attr('hidden', true);
                                                                    $('#action_account_url').attr('hidden', true);
                                                                    $('#action_account_info').attr('hidden', true);
                                                                    document.getElementById('urlIframe').src = '';
                                                                    $('#income-form').submit();
                                                                }else if(response.response_type == 'account'){
                                                                    $('#lbl_payment_action').text('Please Transfer to ' + data.name + ' Virtual Account :');
                                                                    $('#input_payment_action_account').text(response.value_response);

                                                                    $('#name_action_account').text("Name of account : " + response.name_response);
                                                                    $('#exp_action_account').text("Valid until : " + response.expired_response);
                                                                    $('#action_account_account').attr('hidden', false);
                                                                    $('#action_account_url').attr('hidden', true);
                                                                    $('#action_account_barcode').attr('hidden', true);
                                                                    $('#action_account_info').attr('hidden', true);
                                                                    document.getElementById('urlIframe').src = '';
                                                                    $('#income-form').submit();
                                                                }else if(response.response_type == 'info'){
                                                                    $('#lbl_payment_action').text('Please Check to Customers ' + data.name + "'s Account");
                                                                    $('#input_payment_action_account').text("");
                                                                    $('#input_payment_action_info').text(response.value_response);
                                                                    $('#name_action_account').text("");
                                                                    $('#exp_action_account').text("");
                                                                    $('#action_account_account').attr('hidden', true);
                                                                    $('#action_account_url').attr('hidden', true);
                                                                    $('#action_account_barcode').attr('hidden', true);
                                                                    $('#action_account_info').attr('hidden', false);
                                                                    document.getElementById('urlIframe').src = '';
                                                                    $('#income-form').submit();
                                                                }else if(response.response_type == 'url'){
                                                                    $('#lbl_payment_action').text("Please complete requirement below");
                                                                    $('#input_payment_action_account').text("");
                                                                    $('#input_payment_action_info').text("");
                                                                    $('#name_action_account').text("");
                                                                    $('#exp_action_account').text("");
                                                                    $('#action_account_url').attr('hidden', false);
                                                                    $('#action_account_account').attr('hidden', true);
                                                                    $('#action_account_barcode').attr('hidden', true);
                                                                    $('#action_account_info').attr('hidden', true);
                                                                    document.getElementById('urlIframe').src = response.value_response;
                                                                    $('#income-form').submit();
                                                                }else{
                                                                    Swal.fire({
                                                                    title: 'Payment Error',
                                                                    text: response.response_type,
                                                                    icon: 'error',
                                                                    didOpen: () => {
                                                                        $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                                                    },
                                                                    });
                                                                    return;
                                                                }

                                                                startautosave = setInterval(function() {
                                                                Swal.fire({
                                                                        title: 'Payment Success',
                                                                        text: 'Your Payment has been Successful..!!',
                                                                        icon: 'success',
                                                                        didOpen: () => {
                                                                                $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                                                                        },
                                                                        }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        console.log("Tombol OK ditekan");
                                                                        newtransaction();
                                                                    }
                                                                });
                                                            }, 10000);
                                                            }
                                                        });
                                                        }
                                                    });;

                                                // }
                                            }
                                            ,
                                            error: function(error) {

                                                let errorMessage = error.responseJSON?.message || error.responseText || 'Unknown error occurred';

                                                // Jika terjadi kesalahan, tampilkan pesan gagal
                                                Swal.fire({
                                                    title: 'Process Failed!',
                                                    text: errorMessage,
                                                    icon: 'error',
                                                    didOpen: () => {
                                                            $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                                                    },
                                                });
                                            }
                                        });


                            // });
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                // Aksi jika pengguna mengklik "No, cancel!"
                                Swal.fire({
                                    title: 'Cancelled',
                                    text: 'Your action has been cancelled.',
                                    icon: 'error',
                                    didOpen: () => {
                                        // Enable pointer events for SweetAlert elements
                                        $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                    },
                                });
                            }
                        });
                                // });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Aksi jika pengguna mengklik "No, cancel!"
                $('#checkoutModal').modal('show');
                $('#actionModal').modal('hide');
                fetchPaymentChannels();
                Swal.fire({
                    title: 'Cancelled',
                    text: 'Your action has been cancelled.',
                    icon: 'error',
                    didOpen: () => {
                                        $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                    },
            });
            }
                });


        });

        function newtransaction() {
            clearInterval(startautosave);
            window.location.href = '{{ route('incomes.index') }}';
        }

        function fetchPaymentChannels() {
            var paymentMethodId = document.getElementById('payment_method').value;
            var paymentMethodName =  $('#payment_method option:selected').text();
            var paymentChannelSelect = document.getElementById('payment_channel');
            var income_amount = document.getElementById('amount').value;
            $('#payment_fee_info').text('Rp. 0.00');
            $('input[name=payment_fee]').val('0');
            $('#payment_ppn_info').text('Rp. 0.00');
            $('input[name=payment_ppn]').val('0');
            $('#application_fee_info').text('Rp. 0.00');
            $('input[name=application_fee]').val('0');

            $('#grand_total_info').text('Rp. ' + formatRupiah(income_amount));
            $('input[name=grand_total]').val(income_amount);
            $('input[name=paid_amount]').val(income_amount);

            $("#payment_channel").empty();
                $.ajax({
                    url: "{{ url('/get-payment-channels') }}/",
                    method: "GET",
                    data: {
                        'payment_method': paymentMethodId,
                    },
                    dataType: 'json',
                    success: function(data) {

                        if (data.length > 0) {
                            $("#payment_channel").empty();
                            op = '<option value="" disabled="true" selected="true">-Select-</option>';
                            for (var i = 0; i < data.length; i++) {
                                op += '<option value="' + data[i].id + '">' + data[i]
                                    .name + '</option>';
                            }
                            $("#payment_channel").append(op);
                            $('#payment_channel').attr('hidden', false);
                            $('#lbl_payment_channel').attr('hidden', false);
                            $('#submitBtn').attr('hidden', true);
                            $('#continuePayment').attr('hidden', true);

                        }else{
                            $('#submitBtn').attr('hidden', false);
                            $('#lbl_payment_channel').attr('hidden', true);
                            $('#payment_channel').attr('hidden', true);
                            $('#continuePayment').attr('hidden', true);
                            $("#payment_channel").empty();
                        }
                    }
                });
        }


        function fetchdetailchannel() {
            var paymentChannel = document.getElementById('payment_channel').value;
            var selectedOptionText = $('#payment_channel option:selected').text();
            var total_amount_pay = document.getElementById('amount').value;//$('#amount_sale').maskMoney('unmasked')[0];
            // $('input[name=payment_fee]').val(paidAmount + 10000);

            if(paymentChannel != ''){

                $('#continuePayment').attr('hidden', false);
                if (selectedOptionText == 'OVO') {
                    $('#lbl_number_phone').attr('hidden', false);
                    $('#group_number_phone').attr('hidden', false);
                    $('#number_phone').attr('required', true);
                } else {

                    $('#lbl_number_phone').attr('hidden', true);
                    $('#group_number_phone').attr('hidden', true);
                    $('#number_phone').removeAttr('required');
                }


                    $.ajax({
                    url: "{{ url('/get-channel-attribute') }}/",
                    method: "GET",
                    data: {
                        'id': paymentChannel,
                        'amount': total_amount_pay,
                    },

                    dataType: 'json',
                    success: function(data) {
                        $('#payment_fee_info').text(data.payment_fee_masked);
                        $('input[name=payment_fee]').val(data.payment_fee);
                        $('#payment_ppn_info').text(data.payment_ppn_masked);
                        $('input[name=payment_ppn]').val(data.payment_ppn);

                        $('#application_fee_info').text(data.application_fee_masked);
                        $('input[name=application_fee]').val(data.application_fee);

                        $('#grand_total_info').text(data.grand_total_masked);
                        $('input[name=grand_total]').val(data.grand_total);
                        $('input[name=paid_amount]').val(data.grand_total);
                    }
                });
            }
        }
    </script>
@endpush

