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

                            </div>

                            <div class="form-group">
                                <label for="note">Note (If Needed)</label>
                                <textarea name="note" id="note" rows="2" class="form-control">{{  $paymentAccountText }}</textarea>
                                <button wire:click="changeText">Ubah Teks Account</button>
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

                    {{-- <button id='submitBtn' name='submitBtn' type="submit" class="btn btn-primary">Submit</button> --}}
                    <button id='submitBtn' name='submitBtn' type="submit" class="btn btn-primary" hidden>Submit</button>
                    <button id='continuePayment' name='continuePayment' type="button" class="btn btn-success" hidden>Process to Payment</button>
                    <button type="button" class="btn btn-secondary" id="closeModalCheckout" name="closeModalCheckout" >Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="actionModal" tabindex="-1" role="dialog" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="actionModalLabel"></h5>
            </div>
            <div class="modal-body">
                <p id="lbl_payment_action"></p>
                <div class="form-group align-middle text-center" name="payment_action" id="payment_action" hidden>
                    <div class="qris-action_qrcode" name="action_account_barcode" id="action_account_barcode" hidden>
                        <div id="qr-code-container"></div>
                         {{-- {!! QrCode::size(200)->generate('some-random-qr-string') !!} --}}
                    </div>
                    <div  name="action_account_account" id="action_account_account" hidden>
                        <label class="text-primary" style="font-weight: bold; font-size: 24px;" name="input_payment_action_account" id="input_payment_action_account">123456789</label>
                    </div>
                    <label  name="lbl_payment_by" id="lbl_payment_by" for="payment_by">Payment by </span></label>
                    <img  name="payment_by" id="payment_by" src="" style="width: 100px; height: 50px;"/>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" name="manualConfirmation" id="manualConfirmation" hidden>Manual Confirmation</button>
                <button type="button" class="btn btn-warning" name="setToWaiting" id="setToWaiting" hidden>Set to Waiting status</button>
                <button type="button" class="btn btn-success" name="modalConfirmationYes" id="modalConfirmationYes" hidden>Continue</button>
                <button id="btnCancelProcessPayment" name="btnCancelProcessPayment" type="button" class="btn btn-secondary">Cancel</button>
             </div>
        </div>
    </div>
</div>
@push('page_scripts')
<script>


    $(document).on('click', '#continuePayment', function() {
        $('#checkoutModal').modal('hide');
        $('#actionModal').modal('show');
        $('#lbl_payment_action').text('Are you sure want to continue this payment?');
        $('#actionModalLabel').text('Payment Confirmation');


        $('#modalConfirmationYes').attr('hidden', false);
        $('#manualConfirmation').attr('hidden', true);
        $('#setToWaiting').attr('hidden', true);

        $('#input_payment_action_barcode').attr('hidden', true);
        $('#action_account_account').attr('hidden', true);
        $('#action_account_barcode').attr('hidden', true);
        $('#payment_by').attr('hidden', true);
        $('#lbl_payment_by').attr('hidden', true);

    });

    $(document).on('click', '#manualConfirmation', function() {
        $('#checkout-form').submit();
    });

    $(document).on('change', '#payment_method', function() {
        fetchPaymentChannels();
    });

    $(document).on('click', '#btnCancelProcessPayment', function() {
        $('#actionModal').modal('hide');
        $('#checkoutModal').modal('show');
        fetchPaymentChannels();
    });

    $(document).on('click', '#modalConfirmationYes', function() {
        $('#modalConfirmationYes').attr('hidden', true);
        $('#manualConfirmation').attr('hidden', false);
        $('#setToWaiting').attr('hidden', false);
        $('#payment_action').attr('hidden', false);
        $('#payment_by').attr('hidden', false);

        $('#lbl_payment_by').attr('hidden', false);
        var paymentChannel = document.getElementById('payment_channel').value;
        var paidAmount = document.getElementById('paid_amount').value;
        var amount = paidAmount * 100;

            $.ajax({
                url: "{{ url('/get-payment-channel-details') }}/",
                method: "GET",
                data: {
                    'id': paymentChannel,
                },
                dataType: 'json',
                success: function(data) {

                    $('#payment_by').attr('src', data.image_url);
                    if (data.action == 'qrcode') {
                        $('#lbl_payment_action').text('Please Scan Barcode to Process payment');
                        // $('#input_payment_action_barcode').text('12345678910');

                        $('#action_account_barcode').attr('hidden', false);
                        $('#action_account_account').attr('hidden', true);
                    }

                    if(data.action == 'account'){
                        $('#lbl_payment_action').text('Please Transfer to '+ data.name + ' Virtual Account :');
                        // $('#input_payment_action_account').text('12345678910');

                        $('#action_account_account').attr('hidden', false);
                        $('#action_account_barcode').attr('hidden', true);
                    }

                        // $.ajax({
                        //     url: "{{ url('/payment-request') }}/",
                        //     method: "GET",
                        //     data: {
                        //         'payment_channel_id': paymentChannel,
                        //         'amount': amount,
                        //         'action': data.action,
                        //         'source': data.source
                        //     },
                        //     dataType: 'json',
                        //     success: function(data) {
                        //         if (data.length > 0) {
                        //             if (data.action == 'account'){

                        //                 $('#qr-code-container').html('');
                        //                 $('#input_payment_action_account').text(data.value_response);
                        //                 $('#input_payment_action_account').value(data.requestPayment);
                        //                 }else{
                        //                 $('#qr-code-container').html(response.value_response);
                        //                 $('#input_payment_action_account').text("");
                        //                 $('#input_payment_action_account').value(data.requestPayment);
                        //             }

                        //         }
                        //     }
                        // });

                }
            });
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
                            // alert(data);
                            op = '<option value="" disabled="true" selected="true">-Select-</option>';
                            for (var i = 0; i < data.length; i++) {
                                op += '<option value="' + data[i].id + '">' + data[i]
                                    .name + '</option>';
                            }
                            // op += "<option value='9d35ce1d-e124-4085-ab06-697f3c7d3b2c'>Bank BRI</option>";
                            $("#payment_channel").append(op);

                            $('#payment_channel').attr('hidden', false);
                            $('#lbl_payment_channel').attr('hidden', false);

                            $('#submitBtn').attr('hidden', true);
                            $('#continuePayment').attr('hidden', true);
                            $('#modalConfirmationYes').attr('hidden', true);

                        }else{
                            $('#submitBtn').attr('hidden', false);
                            $('#lbl_payment_channel').attr('hidden', true);
                            $('#payment_channel').attr('hidden', true);
                            $('#continuePayment').attr('hidden', true);
                            $('#modalConfirmationYes').attr('hidden', true);
                            $("#payment_channel").empty();
                        }
                    }
                });
        }


        function fetchdetailchannel() {
            var paymentChannel = document.getElementById('payment_channel').value;

            if(paymentChannel != ''){
                $('#continuePayment').attr('hidden', false);
            }
        }

        document.addEventListener('livewire:load', function () {
        // Dengarkan event yang dipanggil oleh Livewire
        Livewire.on('keepModalOpen', () => {
            // Cegah modal tertutup
            $('#yourModalId').modal({backdrop: 'static', keyboard: false});
        });
    });
</script>

@endpush

