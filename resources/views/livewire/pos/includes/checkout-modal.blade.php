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
                                <select class="form-control paymentmethod" name="payment_method" id="payment_method" required onchange="fetchPaymentChannels()">

                                </select>

                                <label for="payment_channel" for="payment_channel"  name="lbl_payment_channel" id="lbl_payment_channel" hidden>Payment Channel <span class="text-danger">*</span></label>
                                <select class="form-control" name="payment_channel" id="payment_channel" onchange="fetchdetailchannel()" hidden>
                                    <option value="">-Select-</option>
                                </select>

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
                    {{-- <button id='submitBtn' name='submitBtn' type="submit" class="btn btn-primary">Submit</button> --}}
                    <button id='submitBtn' name='submitBtn' type="submit" class="btn btn-primary">Submit</button>
                    <button id='continuePayment' name='continuePayment' type="button" class="btn btn-success" id="showModalPayment" hidden>Process to Payment</button>
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
                    {{-- <label name="lbl_payment_action" id="lbl_payment_action" for="action_account_barcode">Please Pay to Virtual Account : </label> --}}
                    <div class="qris-action_qrcode" name="action_account_barcode" id="action_account_barcode" hidden>
                        {!! QrCode::size(200)->generate('some-random-qr-string') !!}
                    </div>
                    <div  name="action_account_account" id="action_account_account" hidden>
                        <label class="text-primary" style="font-weight: bold; font-size: 24px;" name="input_payment_action_account" id="input_payment_action_account">123456789</label>
                    </div>
                    <label for="payment_by">Payment by </span></label>
                        <img  name="payment_by" id="payment_by" src="" style="width: 100px; height: 50px;"/>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" name="manualConfirmation" id="manualConfirmation" hidden>Manual Confirmation</button>
                <button type="button" class="btn btn-warning" name="setToWaiting" id="setToWaiting" hidden>Set to Waiting status</button>
                <button type="button" class="btn btn-success" name="modalConfirmationYes" id="modalConfirmationYes">Continue</button>
                <button id="btnCancelProcessPayment" name="btnCancelProcessPayment" type="button" class="btn btn-secondary">Cancel</button>
             </div>
        </div>
    </div>
</div>
@push('page_scripts')
<script type="text/javascript">


$(document).on('click', '#continuePayment', function() {

    $('#checkoutModal').modal('hide');
    $('#actionModal').modal('show');
    $('#modalConfirmationYes').show();
    $('#lbl_payment_action').text("Are you sure to continue this payment?");

    $('#setToWaiting').attr('hidden', true);
    $('#manualConfirmation').attr('hidden', true);
    });

    $(document).on('click', '#manualConfirmation', function() {
        $('#submitBtn').click();
    });
    $(document).on('click', '#submitBtn', function() {
        $('#submitBtn').click();
    });
    $(document).on('click', '#manualConfirmation', function() {
        //todo perintah simpan status not completed
    });


    $(document).on('click', '#btnCancelProcessPayment', function() {
        $('#actionModal').modal('hide');
        $('#checkoutModal').modal('show');

        $('#setToWaiting').attr('hidden', true);
        $('#manualConfirmation').attr('hidden', true);
        // $('body').css('pointer-events', 'auto');
        fetchPaymentChannels();
    });

    $(document).on('click', '#modalConfirmationYes', function() {


    $('#payment_action').attr('hidden', true);
    // $('#lbl_payment_action').attr('hidden', true);
    $('#action_qrcode').attr('hidden', true);
    $('#action_account_barcode').attr('hidden', true);
    $('#action_account_account').attr('hidden', true);
    $('#input_payment_action_account').attr('hidden', true);
    $('#input_payment_action_barcode').attr('hidden', true);
    $('#modalConfirmationYes').attr('hidden', true);

    $('#setToWaiting').attr('hidden', false);
    $('#manualConfirmation').attr('hidden', false);
    var paymentChannel = document.getElementById('payment_channel').value;

        $.ajax({
            url: "{{ url('/get-payment-channel-details') }}/",
            method: "GET",
            data: {
                'id': paymentChannel,
            },
            dataType: 'json',
            success: function(data) {
            // alert(data);
            $('#payment_by').attr('src', data.image_url);
            if (data.action === 'qrcode') {
                $('#payment_action').attr('hidden', false);
                // $('#lbl_payment_action').attr('hidden', false);
                $('#lbl_payment_action').text('Please Scan Barcode to Process payment');
                $('#action_account_barcode').attr('hidden', false);
                $('#input_payment_action_barcode').attr('hidden', false);
                $('#input_payment_action_barcode').text('12345678910');
                // $('#submitBtn').attr('hidden', true);
                // $('#continuePayment').attr('hidden', true);
            }

            if(data.action === 'account'){
                $('#payment_action').attr('hidden', false);
                // $('#lbl_payment_action').attr('hidden', false);
                $('#lbl_payment_action').text('Please Transfer to '+ data.name + ' Virtual Account :');
                $('#action_account_account').attr('hidden', false);
                $('#input_payment_action_account').attr('hidden', false);
                $('#input_payment_action_account').text('12345678910');
                // $('#submitBtn').attr('hidden', true);
            }
            }
            });
        });


function fetchPaymentChannels() {
    var paymentMethodId = document.getElementById('payment_method').value;
    var paymentMethodName =  $('#payment_method option:selected').text();
    var paymentChannelSelect = document.getElementById('payment_channel');
    $('#setToWaiting').attr('hidden', true);
    $('#manualConfirmation').attr('hidden', true);
    $('#payment_channel').attr('hidden', true);
    $('#lbl_payment_channel').attr('hidden', true);
    $('#submitBtn').attr('hidden', true);
    $('#continuePayment').attr('hidden', true);
    if (paymentMethodName == 'Cash'){
        $('#submitBtn').attr('hidden', false);
    }else{
        $('#submitBtn').attr('hidden', true);
    }
            $.ajax({
                url: "{{ url('/get-payment-channels') }}/",
                method: "GET",
                data: {
                    'payment_method': paymentMethodId,
                },
                dataType: 'json',
                success: function(data) {

                    if (data.length > 0) {
                        // alert(data);
                        $("#payment_channel").empty();
                        op = '<option value="" disabled="true" selected="true">-Select-</option>'
                        for (var i = 0; i < data.length; i++) {
                            op += '<option value="' + data[i].id + '">' + data[i]
                                .name + '</option>';
                        }
                        $("#payment_channel").append(op);
                        $('#payment_channel').attr('hidden', false);
                        $('#lbl_payment_channel').attr('hidden', false);

                    }

                }
            });
}

function fetchdetailchannel() {
    $('#setToWaiting').attr('hidden', true);
    $('#manualConfirmation').attr('hidden', true);
    $('#submitBtn').attr('hidden', true);
    $('#continuePayment').attr('hidden', true);
    $('#payment_action').attr('hidden', true);
    // $('#lbl_payment_action').attr('hidden', true);
    $('#action_qrcode').attr('hidden', true);
    $('#action_account_barcode').attr('hidden', true);
    $('#action_account_account').attr('hidden', true);
    $('#input_payment_action_account').attr('hidden', true);
    $('#input_payment_action_barcode').attr('hidden', true);
    var paymentChannel = document.getElementById('payment_channel').value;

    if(paymentChannel != ''){
        $('#continuePayment').attr('hidden', false);
    }

}
</script>

@endpush

