<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">
                    <i class="bi bi-cart-check text-primary"></i> Confirm Sale
                </h5>

            </div>
            <form id="checkout-form" action="{{ route('app.pos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if (session()->has('checkout_message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="alert-body">
                                <span>{{ session('checkout_message') }}</span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-7">
                            <input type="hidden" value="{{ $customer_id }}" name="customer_id">
                            <input type="hidden" id="payment_id" name="payment_id">
                            <input type="hidden" value="{{ $global_tax }}" name="tax_percentage">
                            <input type="hidden" value="{{ $global_discount }}" name="discount_percentage">
                            <input type="hidden" value="{{ $shipping }}" name="shipping_amount">
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="total_amount">Total Amount <span class="text-danger">*</span></label>
                                        <input id="total_amount" type="text" class="form-control" name="total_amount" value="{{ $total_amount }}" readonly required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="paid_amount">Received Amount <span class="text-danger">*</span></label>
                                        <input id="paid_amount" type="text" class="form-control" name="paid_amount" value="{{ $total_amount }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-control paymentmethod" name="payment_method" id="payment_method" required>

                                </select>

                                <label for="payment_channel" name="lbl_payment_channel" id="lbl_payment_channel" hidden>Payment Channel <span class="text-danger">*</span></label>
                                <select class="form-control" name="payment_channel" id="payment_channel" onchange="fetchdetailchannel()" hidden>
                                    <option value="">-Select-</option>
                                </select>
                                <label for="number_phone" name="lbl_number_phone" id="lbl_number_phone" hidden>OVO Phone Number<span class="text-danger"> *</span></label>
                                <div  class="col-11 row" name="group_number_phone" id="group_number_phone" hidden>
                                    <label class="col-2 text-small align-right">+62</label>
                                    <input type="text" name="number_phone" id="number_phone" class="form-control form-control-sm col-9" oninput="this.value = this.value.replace(/[^0-9]/g, '');">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="note">Note (If Needed)</label>
                                <textarea name="note" id="note" rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Total Products</th>
                                        <td>
                                                <span class="badge badge-success">
                                                    {{ Cart::instance($cart_instance)->count() }}
                                                </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Order Tax ({{ $global_tax }}%)</th>
                                        <td>(+) {{ format_currency(Cart::instance($cart_instance)->tax()) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Discount ({{ $global_discount }}%)</th>
                                        <td>(-) {{ format_currency(Cart::instance($cart_instance)->discount()) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Shipping</th>
                                        <input type="hidden" value="{{ $shipping }}" name="shipping_amount">
                                        <td>(+) {{ format_currency($shipping) }}</td>
                                    </tr>
                                    <tr class="text-primary">
                                        <th>Grand Total</th>
                                        @php
                                            $total_with_shipping = Cart::instance($cart_instance)->total() + (float) $shipping
                                        @endphp
                                        <th>
                                            (=) {{ format_currency($total_with_shipping) }}
                                        </th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button id='submitBtn' name='submitBtn' type="submit" class="btn btn-primary" hidden>Submit</button>
                    <button id='continuePayment' name='continuePayment' type="button" class="btn btn-success" hidden>Process to Payment</button>
                    <button type="button" class="btn btn-secondary" id="closeModalCheckout" name="closeModalCheckout" >Close</button>
                </div>
            </form>
        </div>
    </div>
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
                        <p id="exp_action_account"></p>
                    </div>
                    <div  name="action_account_info" id="action_account_info" hidden>
                        <label class="text-primary" style="font-weight: bold; font-size: 18px;" name="input_payment_action_info" id="input_payment_action_info">123456789</label>
                    </div>
                    <div  name="action_account_url" id="action_account_url" hidden>
                        <iframe id="urlIframe" width="100%" height="450px" frameborder="0"></iframe>
                    </div>

                    <label  name="lbl_payment_by" id="lbl_payment_by" for="payment_by">Payment by : </span></label>
                    <img  name="payment_by" id="payment_by" src="" style="width: 100px; height: 50px;"/>
                </div>
                <p id="lbl_payment_information">If <b>Succeed</b> the amount will automatically add to your <i class="bi bi-cash text-primary"></i><span style="background-color: yellow;"><b>"[Balance]"</b></span>.<br> after settlement process. Please makesure the payment process is successfull.</p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" name="manualConfirmation" id="manualConfirmation" hidden>Manual Confirmation</button>
                <button type="button" class="btn btn-warning" name="setToWaiting" id="setToWaiting" hidden>Set to Waiting status</button>
             </div>
        </div>
    </div>
</div>
@push('page_scripts')
<script>
    $('#checkout-form').on('submit', function(e) {
        e.preventDefault();
        var paid_amount = $('#paid_amount').maskMoney('unmasked')[0];
        $('#paid_amount').val(paid_amount);
        var total_amount = $('#total_amount').maskMoney('unmasked')[0];
        $('#total_amount').val(total_amount);
        var formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: formData,
        });
    });

    $(document).on('click', '#continuePayment', function() {
        var paymentChannel = document.getElementById('payment_channel').value;
        var numberPhone = document.getElementById('number_phone').value;

        var selectedOptionText = $('#payment_channel option:selected').text();
        var paidAmount = $('#paid_amount').maskMoney('unmasked')[0];
        var amount = paidAmount;

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

        $('#manualConfirmation').attr('hidden', true);
        $('#setToWaiting').attr('hidden', true);
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
                                                'number_phone': numberPhone,
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
                                                    });

                                                    $('#actionModal').modal('show');
                                                    $('#checkoutModal').modal('hide');
                                                    $('#manualConfirmation').attr('hidden', false);
                                                    $('#setToWaiting').attr('hidden', false);
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
                                                            $('#checkout-form').submit();
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
                                                            $('#checkout-form').submit();
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
                                                            $('#checkout-form').submit();
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
                                                            $('#checkout-form').submit();
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
                                                        }
                                                    });
                                                // }
                                            }
                                            // ,
                                            // error: function(error) {

                                            //     let errorMessage = error.responseJSON?.message || error.responseText || 'Unknown error occurred';

                                            //     // Jika terjadi kesalahan, tampilkan pesan gagal
                                            //     Swal.fire({
                                            //         title: 'Process Failed!',
                                            //         text: errorMessage,
                                            //         icon: 'error',
                                            //         didOpen: () => {
                                            //                 $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                            //                         },
                                            //     });
                                            // }
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

    $(document).on('change', '#payment_method', function() {
        fetchPaymentChannels();
    });

    $('#actionModal').on('hidden.bs.modal', function () {
        if ($('#checkoutModal').hasClass('show')) {
            $('body').addClass('modal-open');
        }
    });

        function fetchPaymentChannels() {
            var paymentMethodId = document.getElementById('payment_method').value;
            var paymentMethodName =  $('#payment_method option:selected').text();
            var paymentChannelSelect = document.getElementById('payment_channel');

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
            }

        }
</script>

@endpush

