<div>
    <div>
        @if (session()->has('message'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <div class="alert-body">
                    <span>{{ session('message') }}</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
        @endif
        @php

            $source = null;
            if($data){

                $source = $data->getTable();
                $payments = null;
                $invoiceInfo = null;
                $paymentChannel = null;
                if ($source == 'selforder_checkouts'){
                    $payments = \Modules\Sale\Entities\SelforderCheckoutPayment::where('selforder_checkout_id', $data->id)->first();
                    $invoiceInfo = \Modules\PaymentMethod\Entities\PaymentMethod::find($payments->payment_method_id);
                    $paymentChannel = \Modules\PaymentMethod\Entities\PaymentChannel::find($payments->payment_channel_id);

                }
             }


        @endphp
        <div class="table-responsive position-relative">
            <div wire:loading.flex class="col-12 position-absolute justify-content-center align-items-center" style="top:0;right:0;left:0;bottom:0;background-color: rgba(255,255,255,0.5);z-index: 99;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <table class="table table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th class="align-middle">{{ __("sales.show.table.product") }}</th>
                    <th class="align-middle text-center">{{ __("sales.show.table.net_unit_price") }}</th>
                    <th class="align-middle text-center">{{ __("sales.show.table.stock") }}</th>
                    <th class="align-middle text-center">{{ __("sales.show.table.quantity") }}</th>
                    <th class="align-middle text-center">{{ __("sales.show.table.discount") }}</th>
                    <th class="align-middle text-center">{{ __("sales.show.table.tax") }}</th>
                    <th class="align-middle text-center">{{ __("sales.show.table.sub_total") }}</th>
                    <th class="align-middle text-center">{{ __("sales.show.table.action") }}</th>
                </tr>
                </thead>
                <tbody>
                    @if($cart_items->isNotEmpty())
                        @foreach($cart_items as $cart_item)
                            <tr>
                                <td class="align-middle">
                                    {{ $cart_item->name }} <br>
                                    <span class="badge badge-success">
                                        {{ $cart_item->options->code }}
                                    </span>

                                    {{-- @if($source != 'selforder_checkouts')
                                    @include('livewire.includes.product-cart-modal')
                                    @endif --}}
                                </td>

                                <td class="align-middle text-center">
                                    {{ format_currency($cart_item->options->unit_price) }}
                                </td>

                                <td class="align-middle text-center text-center">
                                    <span class="badge badge-info">{{ $cart_item->options->stock . ' ' . $cart_item->options->unit }}</span>
                                </td>

                                <td class="align-middle text-center">
                                    <div class="input-group d-flex justify-content-center">
                                        <input
                                            id="quantity-input-{{ $cart_item->rowId }}"
                                            wire:change="quantityChange($event.target.value,'{{ $cart_item->rowId }}', '{{ $cart_item->id }}')"
                                            style="min-width: 40px; max-width: 90px;"
                                            type="number"
                                            class="form-control"
                                            value="{{ $cart_item->qty }}"
                                            min="1"
                                             onkeydown="if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') { event.preventDefault(); }"
                                             {{ $paymentChannel ?? '' ? 'readonly' : '' }}>
                                    </div>
                                </td>

                                <td class="align-middle text-center">
                                    {{ format_currency($cart_item->options->product_discount) }}
                                </td>

                                <td class="align-middle text-center">
                                    {{ format_currency($cart_item->options->product_tax) }}
                                </td>

                                <td class="align-middle text-center">
                                    {{ format_currency($cart_item->options->sub_total) }}
                                </td>

                                <td class="align-middle text-center">

                                    @if($source == 'selforder_checkouts')
                                    <button type="button" class="btn btn-primary"  id="generate_button_{{ $cart_item->rowId }}"
                                        onclick="generatePhone('{{ $cart_item->rowId }}', '{{ $cart_item->name }}')">
                                        <i class="bi bi-cart"></i> {{ __("sales.show.table.verification") }}
                                    </button>
                                    @else
                                    <a href="#" wire:click.prevent="removeItem('{{ $cart_item->rowId }}')"  {{ $paymentChannel ?? '' ? 'hidden' : '' }}>
                                        <i class="bi bi-x-circle font-2xl text-danger"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center">
                        <span class="text-danger">
                            {{ __("sales.show.table.info_select") }}
                        </span>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-row" hidden>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="tax_percentage">Tax (%)</label>
                <input wire:change="globalTaxChange($event.target.value)"
                onkeydown="if(!/^\d*\.?\d{0,2}$/.test(this.value + event.key) && event.key !== 'Backspace') { event.preventDefault(); }"
                type="text" class="form-control" name="tax_percentage" min="0" max="100" value="{{ $global_tax }}" {{ $data->with_invoice ?? false ? 'readonly' : 'required' }}>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="discount_percentage">Discount (%)</label>
                <input wire:change="globalDiscountChange($event.target.value)"
                onkeydown="if(!/^\d*\.?\d{0,2}$/.test(this.value + event.key) && event.key !== 'Backspace') { event.preventDefault(); }"
                type="text" class="form-control" name="discount_percentage" min="0" max="100" value="{{ $global_discount }}" {{ $data->with_invoice ?? false ? 'readonly' : 'required' }}>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="shipping_amount">Shipping</label>
                <input wire:change="shippingChange($event.target.value)"
                onkeydown="if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') { event.preventDefault(); }"
                type="number" class="form-control" name="shipping_amount" min="0" value="{{ $data->shipping_amount ?? 0  }}" {{ $data->with_invoice ?? false ? 'readonly' : 'required' }} step="0.01">
            </div>
        </div>
    </div>
    <br>
    <div class="row justify-content-md-end">
        <div class="col-md-4">
            <div class="table-responsive">
                <table class="table table-striped">
                    {{-- <tr>
                        <th>Tax ({{ $global_tax }}%)</th>
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
                    </tr> --}}
                    <tr>
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

    <input type="hidden" name="total_amount" value="{{ $total_with_shipping }}">

</div>
