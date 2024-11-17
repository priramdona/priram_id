<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">
                    <i class="bi bi-cart-check text-primary"></i> {{ __('checkout_modal.confirm_selforder') }}
                </h5>

            </div>
            <form id="checkout-form" action="{{ route('app.selforder.mobileorder.store') }}" method="POST">
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
                        <div>
                            <input type="hidden" value="" name="selforder_checkout_id"  id="selforder_checkout_id">
                            <input type="hidden" value="{{ $selforder_business->id }}" name="selforder_business_id"  id="selforder_business_id">
                            <input type="hidden" value="{{ $customers->id }}" name="customer_id"  id="customer_id">
                            <input type="hidden" id="payment_id" name="payment_id">
                            <input type="hidden" id="paylater_plan_id" name="paylater_plan_id">
                            <input type="hidden" id="payment_method_type" name="payment_method_type">
                            <input type="hidden" value="{{ $global_tax }}" name="tax_percentage">
                            <input type="hidden" value="{{ $global_discount }}" name="discount_percentage">
                            <input type="hidden" value="{{ $shipping }}" name="shipping_amount">
                            <div class="form-row" hidden>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="total_amount">{{ __('checkout_modal.total_amount') }} <span class="text-danger">*</span></label>
                                        <input id="total_amount" type="text" class="form-control" name="total_amount" value="{{ $total_amount }}" readonly required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="paid_amount">{{ __('checkout_modal.received_amount') }} <span class="text-danger">*</span></label>
                                        <input id="paid_amount" type="text" class="form-control" name="paid_amount" value="{{ $total_amount }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="payment_method">{{ __('checkout_modal.payment_method') }} <span class="text-danger">*</span></label>
                                <select class="form-control paymentmethod" name="payment_method" id="payment_method" required>

                                </select>

                                <label for="payment_channel" name="lbl_payment_channel" id="lbl_payment_channel" hidden>{{ __('checkout_modal.payment_channel') }} <span class="text-danger">*</span></label>
                                <select class="form-control" name="payment_channel" id="payment_channel" onchange="fetchdetailchannel()" hidden>
                                    <option value="">{{ __('checkout_modal.select') }}</option>
                                </select>
                                <label for="number_phone" name="lbl_number_phone" id="lbl_number_phone" hidden>{{  __('checkout_modal.ovo.phone_number') }}<span class="text-danger"> *</span></label>
                                <div  class="col-11 row" name="group_number_phone" id="group_number_phone" hidden>
                                    <label class="col-2 text-small align-right">+62</label>
                                    <input type="text" name="number_phone" id="number_phone" class="form-control form-control-sm col-9" oninput="this.value = this.value.replace(/[^0-9]/g, '');">

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>{{ __('checkout_modal.summary.total_products') }}</th>
                                            <td>
                                                    <span class="badge badge-success">
                                                        {{ Cart::instance($cart_instance)->count() }}
                                                    </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('checkout_modal.summary.tax') }} ({{ $global_tax }}%)</th>
                                            <td>(+) {{ format_currency(Cart::instance($cart_instance)->tax()) }}</td>
                                        </tr>
                                        {{-- <tr>
                                            <th>{{ __('checkout_modal.summary.discount') }} ({{ $global_discount }}%)</th>
                                            <td>(-) {{ format_currency(Cart::instance($cart_instance)->discount()) }}</td>
                                        </tr> --}}
                                        {{-- <tr>
                                            <th>{{ __('checkout_modal.summary.shipping') }}</th>
                                            <input type="hidden" value="{{ $shipping }}" name="shipping_amount">
                                            <td>(+) {{ format_currency($shipping) }}</td>
                                        </tr> --}}
                                        <tr class="text-primary">
                                            <th>{{ __('checkout_modal.summary.total') }}</th>
                                            @php
                                                $total_with_shipping = Cart::instance($cart_instance)->total() + (float) $shipping
                                            @endphp

                                            <input name="amount_sale" id="amount_sale" type="hidden" value="{{ $total_with_shipping }}" name="shipping_amount">
                                            <td>
                                                (=) {{ format_currency($total_with_shipping) }}
                                            </td>
                                        </tr>
                                        <tr class="text-success">
                                            <th>{{ __('checkout_modal.summary.payment_fee') }}</th>
                                            <input name="payment_fee" id="payment_fee" type="hidden" value="0">
                                            <td id="payment_fee_info">Rp. 0.00</td>
                                        </tr>
                                        {{-- <tr class="text-success">
                                            <th>{{ __('checkout_modal.summary.ppn') }}<span style="font-size: 8px;  font-weight: bold;"> {{ __('checkout_modal.summary.ppn_from_payment') }}</span></th>
                                            <input name="payment_ppn" id="payment_ppn" type="hidden" value="0">
                                            <td id="payment_ppn_info">Rp. 0.00</td>
                                        </tr> --}}
                                        {{-- <tr class="text-success">
                                            <th>{{ __('checkout_modal.summary.application_fee') }}</th>
                                            <input name="application_fee" id="application_fee" type="hidden" value="0">
                                            <td id="application_fee_info">Rp. 0.00</td>
                                        </tr> --}}
                                        <tr class="text-primary">
                                            <th>{{ __('checkout_modal.summary.grand_total') }}</th>
                                            <input name="grand_total" id="grand_total" type="hidden">
                                            <th id="grand_total_info"></th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row" name="payment_warning_information" id="payment_warning_information" hidden>
                    <label class="text-danger" style="font-weight: bold; font-size: 16px;">{{ __('checkout_modal.summary.info') }}</label>

                    </div>
                </div>

                <div class="modal-footer">

                    <button id='submitBtn' name='submitBtn' type="submit" class="btn btn-primary" hidden>{{ __('checkout_modal.submit') }}</button>
                    <button id='continuePayment' name='continuePayment' type="button" class="btn btn-success" hidden>{{ __('checkout_modal.proceed_payment') }}</button>
                    <button type="button" class="btn btn-secondary" id="closeModalCheckout" name="closeModalCheckout" >{{ __('checkout_modal.close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="actionModal" tabindex="-1" role="dialog" aria-labelledby="lbl_payment_action" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span id="lbl_payment_action" name="lbl_payment_action" style="font-size: 18px; color: #ff0000; font-weight: bold;"></span>

            </div>
            <div class="modal-body">
                <div class="form-group align-middle text-center" name="payment_action" id="payment_action" hidden>
                    <div class="get-barcodelbl_payment_action" id="action_account_barcode">
                        <div id="qr-code-container" style="display: flex; justify-content: center; align-items: center; height: 100%;"></div>
                    </div>
                    <div  name="action_account_account" id="action_account_account" hidden>
                        <span style="font-size: 12px; color: #007bff; font-weight: bold;">{{ __('checkout_modal.payment_action.account_no') }}</span><br>
                        <label class="text-primary" style="font-weight: bold; font-size: 24px;" name="input_payment_action_account" id="input_payment_action_account"></label>

                        <p class="form-group align-middle text-left" >{{ __('checkout_modal.payment_action.account_name') }} : <span id="name_action_account" style="font-size: 18px; color: #007bff; font-weight: bold;"></span></p>
                        <p class="form-group align-middle text-left" >{{ __('checkout_modal.payment_action.account_expired') }} : <span id="exp_action_account" style="font-size: 18px; color: #007bff; font-weight: bold;"></span></p>

                    </div>
                    <div  name="action_account_info" id="action_account_info" hidden>
                        <label class="text-primary" style="font-weight: bold; font-size: 18px;" name="input_payment_action_info" id="input_payment_action_info"></label>
                    </div>
                    <div  name="action_account_url" id="action_account_url" hidden>
                        <iframe id="urlIframe" width="100%" height="450px" frameborder="0"></iframe>
                    </div>

                    <div class="card" name="input_payment_detail" id="input_payment_detail" hidden>
                        <div class="card-header d-flex flex-wrap align-items-center">
                            <span id="input_payment_detail_label" style="font-size: 16px; color: #b84a01; font-weight: bold;"></span>

                        </div>
                        <div class="card-body">
                            <p class="form-group align-middle text-left bi bi-envelope text-success"> {{ __('checkout_modal.payment_action.email') }} : <span id="input_payment_detail_email" style="font-size: 18px; color: #007bff; font-weight: bold;"></span></p>
                            <p class="form-group align-middle text-left bi bi-whatsapp text-success"> {{ __('checkout_modal.payment_action.whatsapp') }} : <span id="input_payment_detail_wa" style="font-size: 18px; color: #007bff; font-weight: bold;"></span></p>
                        </div>
                    </div>

                    <label  name="lbl_payment_by" id="lbl_payment_by" for="payment_by">{{ __('checkout_modal.payment_action.payment_by') }} </span></label>
                    <img  name="payment_by" id="payment_by" src="" style="width: 100px; height: 50px;"/>
                    <br>
                    <div>
                        <label class="text-success" style="font-weight: bold; font-size: 24px;" name="nominal_information" id="nominal_information">123456789</label>
                        </div>

                </div>

                <p id="lbl_payment_information">{{ __('checkout_modal.payment_action.footer_info_deducted') }}</p>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-success" name="checkPayment" id="checkPayment" onclick="fetchpaymentstatus()">{{ __('checkout_modal.payment_action.button_check_payment') }}</button>
                <button type="button" class="btn btn-primary" name="manualConfirmation" id="manualConfirmation" onclick="changePaymentMethod()" >{{ __('checkout_modal.payment_action.button_change_payment') }}</button>
                {{-- <button type="button" class="btn btn-warning" name="setToWaiting" id="setToWaiting" hidden>Set to Waiting status</button> --}}
             </div>
        </div>
    </div>
</div>


<div class="modal fade" id="cust_selforder" tabindex="-1" role="dialog" aria-labelledby="lbl_payment_action" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span id="cust_selforder_info" name="cust_selforder_info" style="font-size: 18px; font-weight: bold;">{{ __('checkout_modal.action.selforder.info_customer') }}</span>
            </div>
            <div class="modal-body">
                <div class="form-group align-middle text-left align-items-center col-md-12">
                    <div class="row">
                        <span style="font-size: 12px; color: #007bff; font-weight: bold;">{{ __('checkout_modal.action.selforder.phone') }}</span><br>
                        <label  style="font-weight: bold; font-size: 18px;" name="cust_selforder_phone" id="cust_selforder_phone">{{ $customers->customer_phone }}</label>
                    </div>
                    <div class="row">
                        <span style="font-size: 12px; color: #007bff; font-weight: bold;">{{ __('checkout_modal.action.selforder.first_name') }} </span><br>
                        <input id="cust_selforder_first_name" type="text" class="form-control" value="{{ $customers->customer_first_name }}" name="cust_selforder_first_name" required>
                    </div>
                    <div class="row">
                        <span style="font-size: 12px; color: #007bff; font-weight: bold;">{{ __('checkout_modal.action.selforder.last_name') }} </span><br>
                        <input id="cust_selforder_last_name" type="text" class="form-control" value="{{ $customers->customer_last_name }}" name="cust_selforder_last_name" required>
                    </div>

                    <div class="row">
                        <span style="font-size: 12px; color: #007bff; font-weight: bold;">{{ __('checkout_modal.action.selforder.gender') }} </span><br>
                        {{-- <input type="text" class="form-control" name="gender" required> --}}
                        <select name="cust_selforder_gender" id="cust_selforder_gender" class="form-control" required>
                            <option value="" {{ $customers->gender == '' ? 'selected' : '' }}>{{ __('checkout_modal.action.selforder.select') }}</option>
                            <option value="MALE" {{ $customers->gender == 'MALE' ? 'selected' : '' }}>{{ __('checkout_modal.action.selforder.male') }}</option>
                            <option value="FEMALE" {{ $customers->gender == 'FEMALE' ? 'selected' : '' }}>{{ __('checkout_modal.action.selforder.female') }}</option>
                        </select>
                    </div>

                    <div class="row">
                        <span style="font-size: 12px; color: #007bff; font-weight: bold;">{{ __('checkout_modal.action.selforder.dob') }}</span><br>
                        <input type="date" class="form-control" name="cust_selforder_dob" id="cust_selforder_dob" required value="{{ $customers->dob ? \Carbon\Carbon::parse($customers->dob)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') }}">
                    </div>
                    <div class="row">
                        <span style="font-size: 12px; color: #007bff; font-weight: bold;">{{ __('checkout_modal.action.selforder.email') }}</span><br>
                        <input id="cust_selforder_email" type="text" class="form-control" value="{{ $customers->customer_email }}"  name="cust_selforder_email" required>
                    </div>
                    <div class="row">
                        <span style="font-size: 12px; color: #007bff; font-weight: bold;">{{ __('checkout_modal.action.selforder.address') }}</span><br>
                        <input id="cust_selforder_address" type="text" class="form-control" value="{{ $customers->address }}" name="cust_selforder_address" required>
                    </div>
                    <div class="row">
                        <span style="font-size: 12px; color: #007bff; font-weight: bold;">{{ __('checkout_modal.action.selforder.city') }}</span><br>
                        <input id="cust_selforder_city" type="text" class="form-control" value="{{ $customers->city }}"  name="cust_selforder_city" required>
                    </div>
                    <div class="row">
                        <span style="font-size: 12px; color: #007bff; font-weight: bold;">{{ __('checkout_modal.action.selforder.province') }}</span><br>
                        <input id="cust_selforder_province" type="text" class="form-control" value="{{ $customers->province }}" name="cust_selforder_province" required>
                    </div>
                    <div class="row">
                        <span style="font-size: 12px; color: #007bff; font-weight: bold;">{{ __('checkout_modal.action.selforder.postal_code') }}</span><br>
                        <input id="cust_selforder_postal" type="number" class="form-control" value="{{ $customers->postal_code }}" name="cust_selforder_postal" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cust_selforder_close" name="cust_selforder_close" >{{ __('checkout_modal.action.selforder.button_close') }}</button>
                <button type="button" class="btn btn-primary" name="cust_selforder_process" id="cust_selforder_process">{{ __('checkout_modal.action.selforder.button_proceed') }}</button>
             </div>
        </div>
    </div>
</div>
@push('page_scripts')
<script>
    const messages = @json(__('checkout_modal.continue_payment'));
    var startautosave;
    var isSubmitting;
    var baseSelfOrderSuccessUrl = "{{ route('selforder-checkout.success', ['id' => ':id']) }}";

    function newtransaction() {
        clearInterval(startautosave);
        var id = document.getElementById('selforder_checkout_id').value; // ID yang diinginkan
        var dynamicUrl = getSelfOrderSuccessUrl(id);
        window.location.href = dynamicUrl;
        // window.location.href = '{{ route('app.pos.index') }}';
    }
    function getSelfOrderSuccessUrl(id) {
        return baseSelfOrderSuccessUrl.replace(':id', id);
    }

    document.getElementById('submitBtn').addEventListener('click', function(event) {
        event.preventDefault(); // Mencegah pengiriman form langsung
        var paymentChannel = document.getElementById('payment_channel').value;
        if (paymentChannel.length > 0){
            e.preventDefault();
        }else{
            Swal.fire({
            title: messages.payment_confirmation.title,
            text: messages.payment_confirmation.text,
            icon: 'question',  // Tipe ikon question
            showCancelButton: true,  // Menampilkan tombol cancel
            confirmButtonColor: '#3085d6',  // Warna tombol confirm
            cancelButtonColor: '#d33',  // Warna tombol cancel
            confirmButtonText: messages.payment_confirmation.button_confirm,
            cancelButtonText: messages.payment_confirmation.button_cancel,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                    $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                            },
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#checkout-form').submit();

                }
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Aksi jika pengguna mengklik "No, cancel!"
                    $('#checkoutModal').modal('show');
                    $('#actionModal').modal('hide');
                    fetchPaymentChannels();
                    Swal.fire({
                        title: messages.payment_confirmation.cancelled.title,
                        text: messages.payment_confirmation.cancelled.text,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        icon: 'error',
                        didOpen: () => {$('.swal2-container, .swal2-popup').css('pointer-events', 'auto');},
                    });
                    return;
                }
            });
        }
    });

    $('#checkout-form').on('submit', function(e) {

        if (isSubmitting) return; // Jika sudah dalam proses submit, hentikan

        var paymentChannel = document.getElementById('payment_channel').value;
        if (paymentChannel.length > 0){
            e.preventDefault();
        }
        var formData = $(this).serialize();
        isSubmitting = true; // Set flag sebagai true untuk submit pertama kali

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: formData,
            success: function (response) {
                    $('input[name=selforder_checkout_id]').val(response.selforder_checkout_id);
                },
        });
    });

    $(document).on('click', '#continuePayment', function(){
        var paymentChannel = document.getElementById('payment_channel').value;
        var numberPhone = document.getElementById('number_phone').value;

        var selectedOptionText = $('#payment_channel option:selected').text();
        var amount =  document.getElementById('grand_total').value; //$('#paid_amount').maskMoney('unmasked')[0];
        var sale_amount = document.getElementById('amount_sale').value;// $('#amount_sale').maskMoney('unmasked')[0];;
        var customer_id = document.getElementById('customer_id').value;
        var selforder_business_id = document.getElementById('selforder_business_id').value;

        if (selectedOptionText == 'OVO'){

            if (numberPhone.length < 8) {
                Swal.fire({
                    title: messages.ovo.phone_error.title,
                    text: messages.ovo.phone_error.text,
                    icon: 'error',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
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
        $('#input_payment_detail').attr('hidden', true);

        Swal.fire({
                title: messages.payment_confirmation.title,
                text: messages.payment_confirmation.text,
                icon: 'question',  // Tipe ikon question
                showCancelButton: true,  // Menampilkan tombol cancel
                confirmButtonColor: '#3085d6',  // Warna tombol confirm
                cancelButtonColor: '#d33',  // Warna tombol cancel
                confirmButtonText: messages.payment_confirmation.button_confirm,
                cancelButtonText: messages.payment_confirmation.button_cancel,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                        $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                },
                }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                title: messages.payment_confirmation.confirmed.title,
                html: messages.payment_confirmation.confirmed.html,
                icon: 'info',  // Tipe ikon question
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCancelButton: true,  // Menampilkan tombol cancel
                confirmButtonColor: '#3085d6',  // Warna tombol confirm
                cancelButtonColor: '#d33',  // Warna tombol cancel
                confirmButtonText: messages.payment_confirmation.confirmed.button_proceed,
                cancelButtonText: messages.payment_confirmation.confirmed.button_cancel,
                didOpen: () => {
                    $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                },
                        }).then((result) => {
                            if (result.isConfirmed) {
                                    Swal.fire({
                                    title: messages.payment_confirmation.confirmed.proceed.title,
                                    text: messages.payment_confirmation.confirmed.proceed.text,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                    });
                                        $.ajax({
                                            url: "{{ url('/get-payment-selforder') }}/",
                                            method: "GET",
                                            data: {
                                                'payment_channel_id': paymentChannel,
                                                'amount': amount,
                                                'sale_amount': sale_amount,
                                                'number_phone': numberPhone,
                                                'transaction_type': 'sale',
                                                'customer_id': customer_id,
                                                'selforder_business_id': selforder_business_id,
                                                // 'action': data.action,
                                                // 'source': data.source
                                            },
                                            dataType: 'json',
                                            success: function(response) {
                                                Swal.fire({
                                                    title: messages.payment_confirmation.confirmed.proceed.success.title,
                                                    text: messages.payment_confirmation.confirmed.proceed.success.text,
                                                    allowOutsideClick: false,
                                                    allowEscapeKey: false,
                                                    icon: 'success',
                                                    didOpen: () => {
                                                            $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                                                    },
                                                    })
                                                    // .then((result) => {
                                                        // if (result.isConfirmed) {
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
                                                                // alert(response.payment_request_id);
                                                                $('#payment_by').attr('src', data.image_url);
                                                                $('input[name=payment_id]').val(response.payment_request_id);

                                                                if (response.response_type == 'qrcode') {
                                                                    $('#lbl_payment_action').text(messages.payment_action.qrcode.lbl_payment_action);
                                                                    $('#qr-code-container').html(response.value_response);
                                                                    $('#input_payment_action_account').text("");
                                                                    $('#action_account_barcode').attr('hidden', false);
                                                                    $('#action_account_account').attr('hidden', true);
                                                                    $('#action_account_url').attr('hidden', true);
                                                                    $('#action_account_info').attr('hidden', true);
                                                                    document.getElementById('urlIframe').src = '';
                                                                    $('#checkout-form').submit();
                                                                }else if(response.response_type == 'account'){
                                                                    $('#lbl_payment_action').text(messages.payment_action.account.lbl_payment_action_1 + data.name + messages.payment_action.account.lbl_payment_action_2);
                                                                    $('#input_payment_action_account').text(response.value_response);

                                                                    $('#name_action_account').text(response.name_response);
                                                                    $('#exp_action_account').text(response.expired_response);
                                                                    $('#action_account_account').attr('hidden', false);
                                                                    $('#action_account_url').attr('hidden', true);
                                                                    $('#action_account_barcode').attr('hidden', true);
                                                                    $('#action_account_info').attr('hidden', true);
                                                                    document.getElementById('urlIframe').src = '';
                                                                    $('#checkout-form').submit();
                                                                }else if(response.response_type == 'info'){
                                                                    $('#lbl_payment_action').text(messages.payment_action.info.lbl_payment_action + data.name);
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
                                                                    $('#lbl_payment_action').text(messages.payment_action.url.lbl_payment_action);
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
                                                                }else if(response.response_type == 'direct'){
                                                                    let str = response.name_response;
                                                                    let parts = str.split("|");

                                                                    let namaLengkap = parts[0]; // "Nama Lengkap"
                                                                    let nomorTelepon = parts[1];
                                                                    $('#lbl_payment_action').text(messages.payment_action.direct.lbl_payment_action + namaLengkap);
                                                                    $('#input_payment_action_account').text(nomorTelepon);

                                                                    $('#name_action_account').text(namaLengkap);
                                                                    $('#exp_action_account').text(response.expired_response);
                                                                    $('#action_account_url').attr('hidden', false);
                                                                    $('#action_account_account').attr('hidden', false);
                                                                    $('#action_account_barcode').attr('hidden', true);
                                                                    $('#action_account_info').attr('hidden', true);
                                                                    document.getElementById('urlIframe').src = response.value_response;
                                                                    $('#checkout-form').submit();
                                                                }else if(response.response_type == 'links'){
                                                                    let str = response.name_response;
                                                                    let parts = str.split("|");

                                                                    let namaLengkap = parts[0]; // "Nama Lengkap"
                                                                    let nomorTelepon = parts[1];
                                                                    let email = parts[2];
                                                                    $('#lbl_payment_action').text(messages.payment_action.links.lbl_payment_action + namaLengkap);
                                                                    $('#input_payment_action_account').text(messages.payment_action.links.input_payment_action_account);
                                                                    $('#input_payment_detail_label').text(messages.payment_action.links.input_payment_detail_label);
                                                                    $('#input_payment_detail').attr('hidden', false);
                                                                    $('#input_payment_detail_email').text(email);
                                                                    $('#input_payment_detail_wa').text(nomorTelepon);

                                                                    $('#name_action_account').text(namaLengkap);
                                                                    $('#exp_action_account').text(response.expired_response);
                                                                    $('#action_account_url').attr('hidden', true);
                                                                    $('#action_account_account').attr('hidden', false);
                                                                    $('#action_account_barcode').attr('hidden', true);
                                                                    $('#action_account_info').attr('hidden', true);
                                                                    // document.getElementById('urlIframe').src = response.value_response;
                                                                    $('#checkout-form').submit();
                                                                }else{
                                                                    Swal.fire({
                                                                    title: messages.payment_confirmation.error.title,
                                                                    text: messages.payment_confirmation.error.text,
                                                                    allowOutsideClick: false,
                                                                    allowEscapeKey: false,
                                                                    icon: 'error',
                                                                    didOpen: () => {
                                                                        $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                                                    },
                                                                    });
                                                                    return;
                                                                }

                                                                startautosave = setInterval(function() {
                                                                    console.log("checking...");
                                                                    $.ajax({
                                                                    url: "{{ url('/check-payment') }}/",
                                                                    method: "GET",
                                                                    data: {
                                                                        'payment_request_id': response.payment_request_id,
                                                                    },
                                                                    dataType: 'json',
                                                                    success: function(paymentinfo) {
                                                                        console.log(paymentinfo.status);
                                                                        if(paymentinfo.status == "Paid"){
                                                                            clearInterval(startautosave);
                                                                            Swal.fire({
                                                                                    title: messages.payment_confirmation.success.title,
                                                                                    text: messages.payment_confirmation.success.text,
                                                                                    icon: 'success',
                                                                                    allowOutsideClick: false,
                                                                                    allowEscapeKey: false,
                                                                                    didOpen: () => {
                                                                                            $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                                                                                    },
                                                                                    }).then((result) => {
                                                                                if (result.isConfirmed) {
                                                                                    newtransaction();
                                                                                }
                                                                            });
                                                                        }
                                                                    }
                                                                });
                                                            }, 1000);
                                                            }
                                                        });
                                                        // }
                                                    // });

                                                // }
                                            }
                                            ,
                                            error: function(error) {

                                                let errorMessage = error.responseJSON?.message || error.responseText || 'Unknown error occurred';

                                                // Jika terjadi kesalahan, tampilkan pesan gagal
                                                Swal.fire({
                                                    title: messages.payment_confirmation.error.title,
                                                    text: messages.payment_confirmation.error.text,
                                                    allowOutsideClick: false,
                                                    allowEscapeKey: false,
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
                                    title: messages.payment_confirmation.cancelled.title,
                                    text: messages.payment_confirmation.cancelled.text,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
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
                    title: messages.payment_confirmation.cancelled.title,
                    text: messages.payment_confirmation.cancelled.text,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    icon: 'error',
                    didOpen: () => {
                                        $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                    },
            });
            }
                });


    });
    $(document).on('click', '#cust_selforder_close', function(){
        $('#cust_selforder').modal('hide');
    });

    $(document).on('click', '#cust_selforder_process', function(){
        var cust_selforder_first_name = document.getElementById('cust_selforder_first_name').value; // ID yang diinginkan
        var cust_selforder_last_name = document.getElementById('cust_selforder_last_name').value; // ID yang diinginkan
        var cust_selforder_email = document.getElementById('cust_selforder_email').value; // ID yang diinginkan
        var cust_selforder_address = document.getElementById('cust_selforder_address').value; // ID yang diinginkan
        var cust_selforder_city = document.getElementById('cust_selforder_city').value; // ID yang diinginkan
        var cust_selforder_province = document.getElementById('cust_selforder_province').value; // ID yang diinginkan
        var cust_selforder_postal = document.getElementById('cust_selforder_postal').value; // ID yang diinginkan
        var cust_selforder_gender = document.getElementById('cust_selforder_gender').value; // ID yang diinginkan
        var cust_selforder_dob = document.getElementById('cust_selforder_dob').value; // ID yang diinginkan
        var is_selforder_process = true;

        var customer_id = document.getElementById('customer_id').value;
        if (cust_selforder_first_name.length == 0){
            is_selforder_process = false;
            $('#cust_selforder_first_name').focus();
        }

        let today = new Date();
        let formattedToday = today.toISOString().split('T')[0]; // Format: 'Y-m-d'

        if (!cust_selforder_dob) {
            is_selforder_process = false;
            $('#cust_selforder_dob').focus();
        } else if (cust_selforder_dob === formattedToday) {
            is_selforder_process = false;
            $('#cust_selforder_dob').focus();
        }

        if (cust_selforder_gender.length == 0){
            is_selforder_process = false;
            $('#cust_selforder_gender').focus();
        }

        if (cust_selforder_email.length == 0){
            is_selforder_process = false;
            $('#cust_selforder_email').focus();
        }

        if (cust_selforder_address.length == 0){
            is_selforder_process = false;
            $('#cust_selforder_address').focus();
        }

        if (cust_selforder_city.length == 0){
            is_selforder_process = false;
            $('#cust_selforder_city').focus();
        }

        if (cust_selforder_province.length == 0){
            is_selforder_process = false;
            $('#cust_selforder_province').focus();
        }

        if (cust_selforder_postal.length == 0){
            is_selforder_process = false;
            $('#cust_selforder_postal').focus();
        }

        if (is_selforder_process == false){
            Swal.fire({
                title: messages.customer_selforder.error.title,
                text: messages.customer_selforder.error.text,
                allowOutsideClick: false,
                allowEscapeKey: false,
                icon: 'error',
                didOpen: () => {
                    $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                },
                });
                return;
        }

        $.ajax({
            url: '/customer-selforder/' + customer_id, // Menggunakan URL dari route
            type: 'GET',
            dataType: 'json',
            data: {
                'customer_first_name': cust_selforder_first_name,
                'customer_last_name': cust_selforder_last_name,
                'dob': cust_selforder_dob,
                'customer_email': cust_selforder_email,
                'gender': cust_selforder_gender,
                'city': cust_selforder_city,
                'province': cust_selforder_province,
                'address': cust_selforder_address,
                'postal_code': cust_selforder_postal,
            },
            success: function(response) {
            Swal.fire({
                title: messages.paylater_invoice.confirmation.title,
                icon: 'info',
                html: `
                    <p class="form-group align-middle text-left bi bi-people text-success"> ` + messages.paylater_invoice.confirmation.html_name + ` <span style="font-size: 18px; color: #007bff; font-weight: bold;">` + response.customer_name + `</span></class=>
                    <p class="form-group align-middle text-left bi bi-phone text-success"> ` + messages.paylater_invoice.confirmation.html_phone + ` <span style="font-size: 18px; color: #007bff; font-weight: bold;">` + response.customer_phone + `</span></p>
                    <p class="form-group align-middle text-left bi bi-envelope text-success"> ` + messages.paylater_invoice.confirmation.html_email + ` <span style="font-size: 18px; color: #007bff; font-weight: bold;">` + response.customer_email + `</span></p>
                    <p style="font-size: 16px;">` + messages.paylater_invoice.confirmation.html_info + `</p>
                `,showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: messages.paylater_invoice.confirmation.button_confirm,
                cancelButtonText: messages.paylater_invoice.confirmation.button_cancel,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                },
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetchPaymentChannels();
                    }
                    else if (result.dismiss === Swal.DismissReason.cancel) {

                        Swal.fire({
                            title: messages.payment_confirmation.cancelled.title,
                            text: messages.payment_confirmation.cancelled.text,
                            icon: 'error',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                            },
                        });
                        return;
                    }
                });
            }
            // ,
            // error: function(error) {
            //     let errorMessage = error.responseJSON?.message || error.responseText || 'Unknown error occurred';
            //     Swal.fire({
            //         title: 'Process Failed!',
            //         text: errorMessage,
            //         icon: 'error',
            //         allowOutsideClick: false,
            //         allowEscapeKey: false,
            //         didOpen: () => {
            //                 $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
            //         },
            //     });

            //     return;
            // }
        });
        $('#cust_selforder').modal('hide');
    });

    $(document).on('change', '#payment_method', function() {

        var paymentMethodId = document.getElementById('payment_method').value;
        var customer_id = document.getElementById('customer_id').value;

        // $('#submitBtn').attr('hidden', false);
        $('#lbl_payment_channel').attr('hidden', true);
        $('#payment_channel').attr('hidden', true);
        $('#continuePayment').attr('hidden', true);
        $("#payment_channel").empty();
        $.ajax({
                url: '/get-payment-method-id/' + paymentMethodId, // Menggunakan URL dari route
                type: 'GET',
                data: {
                    'source': 'selforder',
                },
                dataType: 'json',
                success: function(response) {

                    $('input[name=payment_method_type]').val(response.type);

                    if (response.type == 'PAYLATER' || response.type == 'INVOICE'){
                        if (customer_id == ''){
                            Swal.fire({
                                title: messages.paylater_invoice.error.title,
                                text: messages.paylater_invoice.error.text,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                icon: 'error',
                                didOpen: () => {
                                    $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                },
                                });
                                return;
                        }else{
                            $('#cust_selforder').modal('show');
                        }
                    }else{
                        fetchPaymentChannels();
                    }
                },
                error: function(error) {
                    let errorMessage = error.responseJSON?.message || error.responseText || 'Unknown error occurred';
                    Swal.fire({
                        title: messages.payment_confirmation.error.title,
                        text: messages.payment_confirmation.error.text,
                        icon: 'error',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                                $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                        },
                    });

                    return;
                }
            });

    });

    $('#actionModal').on('hidden.bs.modal', function () {
        if ($('#checkoutModal').hasClass('show')) {
            $('body').addClass('modal-open');
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

    function fetchPaymentChannels() {
        var paymentMethodId = document.getElementById('payment_method').value;
        var paymentMethodName =  $('#payment_method option:selected').text();
        var paymentChannelSelect = document.getElementById('payment_channel');
        var sale_amount = document.getElementById('amount_sale').value;

        $('#payment_fee_info').text('Rp. 0.00');
        $('input[name=payment_fee]').val('0');
        $('#payment_ppn_info').text('Rp. 0.00');
        $('input[name=payment_ppn]').val('0');
        $('#application_fee_info').text('Rp. 0.00');
        $('input[name=application_fee]').val('0');

        $('#grand_total_info').text('Rp. ' + formatRupiah(sale_amount));
        $('input[name=grand_total]').val(sale_amount);
        $('input[name=paid_amount]').val(sale_amount);

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
                        op = '<option value="" disabled="true" selected="true">' +  @json(__('checkout_modal.select'), JSON_UNESCAPED_UNICODE) + '</option>';
                        for (var i = 0; i < data.length; i++) {
                            op += '<option value="' + data[i].id + '">' + data[i]
                                .name + '</option>';
                        }
                        $("#payment_channel").append(op);
                        $('#payment_channel').attr('hidden', false);
                        $('#payment_warning_information').attr('hidden', false);

                        $('#lbl_payment_channel').attr('hidden', false);
                        $('#submitBtn').attr('hidden', true);
                        $('#continuePayment').attr('hidden', true);

                    }else{
                        $('#submitBtn').attr('hidden', false);
                        $('#lbl_payment_channel').attr('hidden', true);
                        $('#payment_warning_information').attr('hidden', true);

                        $('#payment_channel').attr('hidden', true);
                        $('#continuePayment').attr('hidden', true);
                        $("#payment_channel").empty();
                    }
                }
            });
    }

    function changePaymentMethod() {
        var payment_id = document.getElementById('payment_id').value;
        var selforder_checkout_id = document.getElementById('selforder_checkout_id').value;
        var customer_id = document.getElementById('customer_id').value;

        Swal.fire({
                title: messages.change_payment.title,
                text: messages.change_payment.text,
                icon: 'question',  // Tipe ikon question
                showCancelButton: true,  // Menampilkan tombol cancel
                confirmButtonColor: '#3085d6',  // Warna tombol confirm
                cancelButtonColor: '#d33',  // Warna tombol cancel
                confirmButtonText: messages.change_payment.button_confirm,
                cancelButtonText: messages.change_payment.button_cancel,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                        $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                },
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('/change-payment') }}/",
                            method: "GET",
                            data: {
                                'payment_request_id': payment_id,
                                'selforder_checkout_id': selforder_checkout_id,
                                'customer_id': customer_id,
                            },
                            dataType: 'json',
                            success: function(paymentinfo) {
                                $('input[name=selforder_checkout_id]').val('');
                                isSubmitting = false; // Set flag sebagai true untuk submit pertama kali
                                clearInterval(startautosave);
                                $('#actionModal').modal('hide');
                                $('#checkoutModal').modal('show');
                            }
                            });

                    }
        });
    }


    function fetchpaymentstatus() {
        var payment_id = document.getElementById('payment_id').value;
        $.ajax({
            url: "{{ url('/check-payment') }}/",
            method: "GET",
            data: {
                'payment_request_id': payment_id,
            },
            dataType: 'json',
            success: function(paymentinfo) {

                if(paymentinfo.status == "Paid"){
                    clearInterval(startautosave);
                    Swal.fire({
                            title: messages.payment_confirmation.success.title,
                            text: messages.payment_confirmation.success.text,
                            icon: 'success',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                    $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                            },
                            }).then((result) => {
                        if (result.isConfirmed) {
                            newtransaction();
                        }
                    });
                }else{
                    Swal.fire({
                            title: messages.waiting_payment.title,
                            text: messages.waiting_payment.text,
                            icon: 'info',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                    $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                            },
                            })
                }
            }
        });
    }
    function fetchdetailchannel() {
        var paymentChannel = document.getElementById('payment_channel').value;
        var selectedOptionText = $('#payment_channel option:selected').text();
        var payment_method_type = document.getElementById('payment_method_type').value;
        var total_amount_pay = document.getElementById('amount_sale').value;

        var paymentMethodId = document.getElementById('payment_method').value;
        //proses Plan dipindah ketika Go ahead
        var customer_id = document.getElementById('customer_id').value;

        $.ajax({
            url: '/get-payment-channel-id/' + paymentChannel,
            type: 'GET',
            dataType: 'json',
            success: function(response) {

                if (parseFloat(total_amount_pay) < parseFloat(response.min)){
                        Swal.fire({
                        title: messages.amount.invalid_min.title,
                        text: messages.amount.invalid_min.text,
                        icon: 'error',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                                $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                        },
                    });
                    $('#continuePayment').attr('hidden', true);

                    return;
                }
                if (parseFloat(total_amount_pay) > parseFloat(response.max)){
                        Swal.fire({
                        title: messages.amount.invalid_max.title,
                        text: messages.amount.invalid_max.text,
                        icon: 'error',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                                $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                        },
                    });
                    $('#continuePayment').attr('hidden', true);

                    return;
                }

                if (response.code == 'OVO') {
                    $('#lbl_number_phone').attr('hidden', false);
                    $('#group_number_phone').attr('hidden', false);
                    $('#number_phone').attr('required', true);
                } else {
                    $('#lbl_number_phone').attr('hidden', true);
                    $('#group_number_phone').attr('hidden', true);
                    $('#number_phone').removeAttr('required');
                }
            },
            error: function(error) {
                let errorMessage = error.responseJSON?.message || error.responseText || 'Unknown error occurred';
                Swal.fire({
                    title: messages.payment_confirmation.error.title,
                    text: messages.payment_confirmation.error.text,
                    icon: 'error',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                            $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                    },
                });
                return;
            }
        });

        if(paymentChannel != ''){
                $('#continuePayment').attr('hidden', false);
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

