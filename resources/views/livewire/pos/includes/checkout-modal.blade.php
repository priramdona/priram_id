<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">
                    <i class="bi bi-cart-check text-primary"></i> {{ __('checkout_modal.confirm_sale') }}
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
                        <div>
                            <input type="hidden" value="{{ $customer_id }}" name="customer_id">
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
                                <label for="note">{{  __('checkout_modal.note') }}</label>
                                <textarea name="note" id="note" rows="2" class="form-control"></textarea>
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
                                        {{-- <tr>
                                            <th>{{ __('checkout_modal.summary.tax') }} ({{ $global_tax }}%)</th>
                                            <td>(+) {{ format_currency(Cart::instance($cart_instance)->tax()) }}</td>
                                        </tr> --}}
                                        <tr>
                                            <th>{{ __('checkout_modal.summary.discount') }} ({{ $global_discount }}%)</th>
                                            <td>(-) {{ format_currency(Cart::instance($cart_instance)->discount()) }}</td>
                                        </tr>
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
                                        <tr class="text-success">
                                            <th>{{ __('checkout_modal.summary.ppn') }} <span style="font-size: 8px;">{{ __('checkout_modal.summary.ppn_from_payment') }}</span></th>
                                            <input name="payment_ppn" id="payment_ppn" type="hidden" value="0">
                                            <td id="payment_ppn_info">Rp. 0.00</td>
                                        </tr>
                                        {{-- <tr class="text-success">
                                            <th>{{ __('checkout_modal.summary.application_fee') }}</th>
                                            <input name="application_fee" id="application_fee" type="hidden" value="0">
                                            <td id="application_fee_info"><span style="font-size: 14px; color: #007bff;">{{ __('checkout_modal.summary.free') }}</span></td>
                                        </tr> --}}
                                        <tr class="text-primary">
                                            <th>{{ __('checkout_modal.summary.grand_total') }}</th>
                                            <input name="grand_total" id="grand_total" class="text-primary" style="font-weight: bold;" type="hidden">
                                            <th id="grand_total_info"></th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

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
                    <div name="action_account_url" id="action_account_url" hidden>
                        <iframe id="urlIframe" width="100%" height="400px" frameborder="0"></iframe>
                        <button id="refreshIframe" class="btn btn-primary mt-2">{{ __('checkout_modal.payment_action.refresh') }}</button>
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

                    <label  name="lbl_payment_by" id="lbl_payment_by" for="payment_by">{{ __('checkout_modal.payment_action.payment_by') }}  </span></label>
                    <img  name="payment_by" id="payment_by" src="" style="width: 100px; height: 50px;"/>
                    <br>
                    <div>
                        <label class="text-success" style="font-weight: bold; font-size: 24px;" name="nominal_information" id="nominal_information"></label>
                    </div>
                </div>
                {{-- <p id="lbl_payment_information">{{ __('checkout_modal.payment_action.footer_info') }}</p> --}}

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" name="checkPayment" id="checkPayment" onclick="fetchpaymentstatus()">{{ __('checkout_modal.payment_action.button_check_payment') }}</button>
                <button type="button" class="btn btn-primary" name="manualConfirmation" id="manualConfirmation" onclick="manualnewtransacation()" >{{ __('checkout_modal.payment_action.button_new_transaction') }}</button>
                {{-- <button type="button" class="btn btn-warning" name="setToWaiting" id="setToWaiting" hidden>Set to Waiting status</button> --}}
             </div>
        </div>
    </div>
</div>
@push('page_scripts')
<script>
    const messages = @json(__('checkout_modal.continue_payment'));
    var isSubmitting = false;
    var startautosave;

      // Tombol untuk refresh iframe
    document.getElementById('refreshIframe').addEventListener('click', function() {
        const iframe = document.getElementById('urlIframe');
        iframe.src = iframe.src; // Reload hanya iframe
    });

    function newtransaction() {
        clearInterval(startautosave);
        window.location.href = '{{ route('app.pos.index') }}';
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
        isSubmitting = true; // Set flag sebagai true untuk submit pertama kali

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

    // $(document).on('click', '#submitBtn', function() {
    //     $('#checkout-form').submit();
    // });

    $(document).on('click', '#continuePayment', function()
    {
        var paymentChannel = document.getElementById('payment_channel').value;
        var numberPhone = document.getElementById('number_phone').value;

        var selectedOptionText = $('#payment_channel option:selected').text();
        var amount =  document.getElementById('grand_total').value; //$('#paid_amount').maskMoney('unmasked')[0];
        var sale_amount = document.getElementById('amount_sale').value;// $('#amount_sale').maskMoney('unmasked')[0];;
        var customer_id = document.getElementById('customer_id').value;

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
                                            url: "{{ url('/get-payment') }}/",
                                            method: "GET",
                                            data: {
                                                'payment_channel_id': paymentChannel,
                                                'amount': amount,
                                                'sale_amount': sale_amount,
                                                'number_phone': numberPhone,
                                                'transaction_type': 'sale',
                                                'customer_id': customer_id,
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
                                                                    $('#action_account_account').attr('hidden', true);
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

                                                                    $.ajax({
                                                                    url: "{{ url('/check-payment') }}/",
                                                                    method: "GET",
                                                                    data: {
                                                                        'payment_request_id': response.payment_request_id,
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
                    'source': 'pos',
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
                            $.ajax({
                                url: '/customer-id/' + customer_id, // Menggunakan URL dari route
                                type: 'GET',
                                dataType: 'json',
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

        function manualnewtransacation() {
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
                                title: messages.new_transaction.title,
                                text: messages.new_transaction.text,
                                icon: 'question',  // Tipe ikon question
                                showCancelButton: true,  // Menampilkan tombol cancel
                                confirmButtonColor: '#3085d6',  // Warna tombol confirm
                                cancelButtonColor: '#d33',  // Warna tombol cancel
                                confirmButtonText: messages.new_transaction.button_confirm,
                                cancelButtonText: messages.new_transaction.button_wait,
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

