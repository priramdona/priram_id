<div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
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

                <div class="form-group">
                    <label for="customer_id"><span style="font-size: 12px;  font-weight: bold;">Welcome</span></label>
                    <div class="form-group">
                        <input type="hidden" value="{{ $selforder_business->id }}" name="selforder_business_id_info">
                        <input type="hidden" value="{{ $customers->id }}" name="customer_id_info">
                        <input type="hidden" value="{{ $customers->customer_name }}" name="customer_name_info">
                        <input type="hidden" value="{{ $customers->customer_phone }}" name="customer_phone_info">
                        <input type="hidden" value="{{ $customers->customer_email }}" name="customer_email_info">
                        <div class="table-responsive">
                        <table border="0" class="table table-hover text-right">
                            <tr>

                                <td style="width: 30%; text-align: left; padding: 10px 10px 10px 10px; border-top: 1px solid #d9d7e0; white-space: nowrap; vertical-align: top; font-size: 10px;">
                                    Customer Information
                                </td>
                                <td style="width: 70%; margin: 10px 0px 10px 10px; border-top: 1px solid #d9d7e0; font-size: 10px;">
                                    <div style="font-weight: bold">{{ $customers->customer_name }}</div>
                                    <div>Email: {{ $customers->customer_email }}</div>
                                    <div>Phone: {{ $customers->customer_phone }}</div>

                                </td>
                            </tr>

                        </table>
                    </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover text-right">
                        <thead class="thead-default">
                            <tr class="text-center">
                                <th class="align-middle"></th>
                                <th class="align-middle">Product</th>
                                <th class="align-middle">Price</th>
                                <th class="align-middle">Quantity</th>
                                <th class="align-middle"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($cart_items->isNotEmpty())
                            @foreach($cart_items as $cart_item)
                                <tr>
                                    <td class="text-left text-center">
                                            @if($cart_item->options->image)
                                                <img src="{{ $cart_item->options->image }}" alt="{{ $cart_item->name }}" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                            @endif
                                    </td>
                                    <td class="text-left text-center">
                                        {{ $cart_item->name }} <br>
                                        <span class="badge badge-success">
                                            {{ $cart_item->options->code }}
                                        </span>
                                    </td>
                                    <td class="text-right text-center">
                                        {{ str_replace('Rp. ','', format_currency($cart_item->price)) }}
                                    </td>
                                    <td class="text-right text-center">
                                        @include('livewire.includes.product-cart-quantity')
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="#" wire:click.prevent="removeItem('{{ $cart_item->rowId }}')">
                                            <i class="bi bi-x-circle font-2xl text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center">
                                    <span class="text-danger">
                                        Please search & select products!
                                    </span>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th>Discount ({{ $global_discount }}%)</th>
                                <td>(-) {{ format_currency(Cart::instance($cart_instance)->discount()) }}</td>
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

            <div class="form-group d-flex justify-content-center flex-wrap mb-0">
                <button wire:click="resetCart" type="button" class="btn btn-pill btn-danger mr-3"><i class="bi bi-x"></i> Reset</button>
                <button wire:loading.attr="disabled" wire:click="proceed({{ $total_with_shipping }})" type="button" class="btn btn-pill btn-primary" {{  $total_amount == 0 ? 'disabled' : '' }}><i class="bi bi-check"></i> Proceed</button>
            </div>

            <div class="modal fade" id="applyAction" tabindex="-1" role="dialog" aria-labelledby="lbl_payment_action" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="lbl_payment_action">Please fill Destination Phone Number</h5>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive" id="table-wrapper">
                                <table  class="table table-bordered table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="width: 40%;">Phone Number</th>
                                            <th class="w-auto">Product Name</th>
                                            <th style="width: 20%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="actiondata">
                                        @foreach($itemActions as $itemAction)
                                        <tr  id="row_{{ $itemAction['action_id'] }}">
                                            <td>
                                                <input type="text" id="phone_number_{{ $itemAction['action_id'] }}" class="form-control" />
                                            </td>
                                            <div class="d-flex align-items-center">
                                            <td>{{ $itemAction['product_name'] }}</td>
                                            </div>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                <button type="button" class="btn btn-primary"  id="generate_button_{{ $itemAction['action_id'] }}"
                                                    onclick="generatePhone('{{ $itemAction['action_id'] }}', '{{ $itemAction['product_name'] }}')">
                                                    <i class="bi bi-phone"></i> Apply
                                                </button>
                                            </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id='proccedaction' type="button" class="btn btn-primary" hidden>
                                Procced
                            </button>
                            <button type="button" class="btn btn-secondary" id="closeModalAction" name="closeModalAction" >Close</button>
                         </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{--Checkout Modal--}}
    {{-- @include('livewire.pos.includes.checkout-modal')
     --}}
     @include('livewire.mobile-order.includes.checkout-modal')
     {{-- @include('livewire.pos.includes.checkout-payment') --}}

</div>
@section('styles')
    <style>
        /* Kustomisasi CSS untuk tampilan mobile */
        @media (max-width: 768px) {
            .table-responsive table {
                font-size: 12px;
            }
            .table-responsive th, .table-responsive td {
                padding: 0.5rem;
            }
            .table-responsive .badge {
                font-size: 10px;
            }
            .table-responsive i {
                font-size: 1.5rem;
            }
        }
    </style>
@endsection
