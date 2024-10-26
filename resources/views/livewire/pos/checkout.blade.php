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
                    <label for="customer_id">Customer</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                                <i class="bi bi-person-plus"></i>
                            </a>
                        </div>
                        <select wire:model.live="customer_id" id="customer_id" class="form-control">
                            <option value="" selected>Not Registered</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover text-right">
                        <thead class="thead-default">
                    {{-- <table class="table table-bordered table-hover table-sm">
                        <thead> --}}
                            <tr class="text-center">
                                <th class="align-middle">Product</th>
                                <th class="align-middle">Price</th>
                                <th class="align-middle">Quantity</th>
                                <th class="align-middle">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($cart_items->isNotEmpty())
                            @foreach($cart_items as $cart_item)
                                <tr>
                                    <td class="text-left">
                                        {{ $cart_item->name }} <br>
                                        <span class="badge badge-success">
                                            {{ $cart_item->options->code }}
                                        </span>
                                        @include('livewire.includes.product-cart-modal')
                                    </td>
                                    <td class="text-right">
                                        {{ format_currency($cart_item->price) }}
                                    </td>
                                    <td class="text-right">
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
                            {{-- <tr>
                                <th>Order Tax ({{ $global_tax }}%)</th>
                                <td>(+) {{ format_currency(Cart::instance($cart_instance)->tax()) }}</td>
                            </tr> --}}
                            <tr>
                                <th>Discount ({{ $global_discount }}%)</th>
                                <td>(-) {{ format_currency(Cart::instance($cart_instance)->discount()) }}</td>
                            </tr>
                            {{-- <tr>
                                <th>Shipping</th>
                                <input type="hidden" value="{{ $shipping }}" name="shipping_amount">
                                <td>(+) {{ format_currency($shipping) }}</td>
                            </tr> --}}
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

            <div class="form-row">
                {{-- <div class="col-lg-4">
                    <div class="form-group">
                        <label for="tax_percentage">Order Tax (%)</label>
                        <input wire:model.blur="global_tax" type="number" class="form-control" min="0" max="100" value="{{ $global_tax }}" required>
                    </div>
                </div> --}}
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="discount_percentage">Discount (%)</label>
                        <input wire:model.blur="global_discount" onkeydown="if(!/^\d*\.?\d{0,2}$/.test(this.value + event.key) && event.key !== 'Backspace') { event.preventDefault(); }
                        const newValue = parseFloat(this.value + event.key);
                                if (newValue > 100 || (newValue < 0 && this.value.length > 0)) {
                                    event.preventDefault();}
                                    " type="number" class="form-control" min="0" max="100" value="{{ $global_discount }}" required>
                    </div>
                </div>
                {{-- <div class="col-lg-4">
                    <div class="form-group">
                        <label for="shipping_amount">Shipping</label>
                        <input wire:model.blur="shipping"onkeydown="if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') { event.preventDefault(); }" type="number" class="form-control" min="0" value="0" required step="0.01">
                    </div>
                </div> --}}

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
                                        <!-- Looping hasil dari $result -->
                                        @foreach($itemActions as $itemAction)
                                        <tr  id="row_{{ $itemAction['action_id'] }}">
                                            <td>
                                                <!-- Set unique ID untuk setiap input phone_number -->
                                                <input type="text" id="phone_number_{{ $itemAction['action_id'] }}" class="form-control" />
                                            </td>
                                            <div class="d-flex align-items-center">
                                            <td>{{ $itemAction['product_name'] }}</td>
                                            </div>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                <!-- Tombol Generate memanggil JavaScript untuk mengambil nilai phone_number dan mengirim ke Livewire -->
                                                <button type="button" class="btn btn-primary"  id="generate_button_{{ $itemAction['action_id'] }}"
                                                    onclick="generatePhone('{{ $itemAction['action_id'] }}', '{{ $itemAction['product_name'] }}')">
                                                    <i class="bi bi-phone"></i> Apply
                                                </button>
                                                       <!-- Tombol Remove -->
                                                {{-- <button type="button" class="btn btn-danger"
                                                    onclick="removeRow('{{ $itemAction['action_id'] }}')">
                                                    <i class="bi bi-trash"></i> Remove --}}
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
     @include('livewire.pos.includes.checkout-modal')
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
