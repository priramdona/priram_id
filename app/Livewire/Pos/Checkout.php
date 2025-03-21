<?php

namespace App\Livewire\Pos;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;
use Modules\PaymentMethod\Entities\PaymentChannel;
use Modules\PaymentMethod\Entities\PaymentMethod;
use Modules\Product\Entities\Product;
use Illuminate\Support\Str;
class Checkout extends Component
{

    public $listeners = ['productSelected', 'addRowToTable', 'discountModalRefresh'];

    public $cart_instance;
    public $customers;
    public $global_discount;
    public $global_tax;
    public $shipping;
    public $quantity;
    public $check_quantity;
    public $discount_type;
    public $item_discount;
    public $data;
    public $customer_id;
    public $total_amount;
    public $payment_method;
    public $quantity_action_existing;
    public $payment_channels = [];
    public $itemActions = [];

    public function mount($cartInstance, $customers) {
        $this->cart_instance = $cartInstance;
        $this->customers = $customers;

        $this->global_discount = 0;
        $this->global_tax = 0;
        $this->shipping = 0.00;
        $this->check_quantity = [];
        $this->quantity = [];
        $this->discount_type = [];
        $this->item_discount = [];
        $this->total_amount = 0;
    }


    public function hydrate() {
        $this->total_amount = $this->calculateTotal();
    }

    public function render() {
        $cart_items = Cart::instance($this->cart_instance)->content();

        return view('livewire.pos.checkout', [
            'cart_items' => $cart_items
        ]);
    }

    public function proceed($grandTotal) {

        if (blank($this->itemActions)){
            $this->dispatch('dispatchBrowserEvent', ['grandTotal' => $grandTotal]);
        }else{
            $this->dispatch('dispatchBrowserAction');
        }

        // if ($this->customer_id != null) {
        // } else {
        //     session()->flash('message', 'Please Select Customer!');
        // }

    // Misalnya menghitung grand total berdasarkan total_with_shipping dan paidAmount
        // $grandTotal = $this->total_with_shipping + $paidAmount;

        // // Dispatch event ke frontend dengan nilai grandTotal
        // $this->dispatchBrowserEvent('update-grand-total', [
        //     'grandTotal' => $grandTotal
        // ]);
    }

    public function calculateTotal() {
        return Cart::instance($this->cart_instance)->total() + $this->shipping;
    }

    public function resetCart() {
        Cart::instance($this->cart_instance)->destroy();
    }

    public function quantityChange($quantityItem, $rowId, $cartItemId) {
        if ($quantityItem == 0) {
            $quantityItem = 1;
        }

        $this->quantity_action_existing = $this->quantity[$cartItemId];
        $this->quantity[$cartItemId] = $quantityItem;
        $this->updateQuantity($rowId,$cartItemId);
    }
    public function productSelected($product) {

        $cart = Cart::instance($this->cart_instance);

        $exists = $cart->search(function ($cartItem, $rowId) use ($product) {
            return $cartItem->id == $product['id'];
        });

        if ($exists->isNotEmpty()) {
            $rowId = $cart->search(function ($cartItem, $rowId) use ($product) {
                return $cartItem->id === $product['id'];
            })->first()->rowId;
            $currentQty = $cart->get($rowId)->qty;
            $this->quantity_action_existing = $currentQty;
            $this->quantity[$product['id']] = $currentQty + 1;
            $this->updateQuantity($rowId,$product['id']);

            return;
        }

        $image_url = isset($product['media'][0]['original_url']) ? $product['media'][0]['original_url'] : null;

        $dataAdd = $cart->add([
            'id'      => $product['id'],
            'name'    => $product['product_name'],
            'qty'     => 1,
            'price'   => $this->calculate($product)['price'],
            'weight'  => 1,
            'options' => [
                'image' => $image_url,
                'product_discount'      => 0.00,
                'product_discount_type' => 'fixed',
                'sub_total'             => $this->calculate($product)['sub_total'],
                'code'                  => $product['product_code'],
                'stock'                 => $product['product_quantity'],
                'unit'                  => $product['product_unit'],
                'product_tax'           => $this->calculate($product)['product_tax'],
                'unit_price'            => $this->calculate($product)['unit_price']
            ]
        ]);

        $rowId = $dataAdd->rowId;

        if ($product['is_action'] == true){
            $this->itemActions[] = [
                'row_id' => $rowId,
                'action_id'=>  str::orderedUuid()->toString(),
                'product_id'=> $product['id'],
                'product_name'=> $product['product_name'],
            ];
        }

        $this->check_quantity[$product['id']] = $product['product_quantity'];
        $this->quantity[$product['id']] = 1;
        $this->discount_type[$product['id']] = 'fixed';
        $this->item_discount[$product['id']] = 0;
        $this->total_amount = $this->calculateTotal();
        $this->dispatch('dispatchProductSelected');
    }

    public function removeItem($row_id) {

        $cartInstance = Cart::instance($this->cart_instance); // Menggunakan instance yang sesuai

        $item = $cartInstance->search(function ($cartItem, $rowIdKey) use ($row_id) {
            return $cartItem->rowId === $row_id;
        })->first();

        $productId = $item->id;

        $this->itemActions = array_values(array_filter($this->itemActions, function($item) use ($productId) {
            return $item['product_id'] !== $productId;
        }));

        $cartInstance->remove($row_id);
    }

    public function updatedGlobalTax() {
        Cart::instance($this->cart_instance)->setGlobalTax((integer)$this->global_tax);
    }

    public function updatedGlobalDiscount() {
        Cart::instance($this->cart_instance)->setGlobalDiscount((integer)$this->global_discount);
    }

    public function updateQuantity($row_id, $product_id) {

        if ($this->check_quantity[$product_id] < $this->quantity[$product_id]) {
            session()->flash('message', __('controller.session.flash.qty_not_available'));

            return;
        }

        Cart::instance($this->cart_instance)->update($row_id, $this->quantity[$product_id]);

        $cart_item = Cart::instance($this->cart_instance)->get($row_id);

        $cartUpdate = Cart::instance($this->cart_instance)->update($row_id, [
            'options' => [
                'sub_total'             => $cart_item->price * $cart_item->qty,
                'code'                  => $cart_item->options->code,
                'stock'                 => $cart_item->options->stock,
                'unit'                  => $cart_item->options->unit,
                'product_tax'           => $cart_item->options->product_tax,
                'unit_price'            => $cart_item->options->unit_price,
                'product_discount'      => $cart_item->options->product_discount,
                'product_discount_type' => $cart_item->options->product_discount_type,
            ]
        ]);

        $product = Product::find($product_id);

        $this->itemActions = array_values(array_filter($this->itemActions, function($item) use ($product_id) {
            return $item['product_id'] !== $product_id;
        }));

        if ($product['is_action'] == true){
        for ($i =1; $i <= $this->quantity[$product_id]; $i++) {
            $this->itemActions[] = [
                'row_id' => $cartUpdate->rowId,
                'action_id'=>  str::orderedUuid()->toString(),
                'product_id'=> $product['id'],
                'product_name'=> $product['product_name'],
            ];
        }
        }
    }

    public function updatedDiscountType($value, $name) {
        $this->item_discount[$name] = 0;
    }

    public function discountModalRefresh($product_id, $row_id) {
        $this->updateQuantity($row_id, $product_id);
    }

    public function setProductDiscount($row_id, $product_id) {
        $cart_item = Cart::instance($this->cart_instance)->get($row_id);

        if ($this->discount_type[$product_id] == 'fixed') {
            Cart::instance($this->cart_instance)
                ->update($row_id, [
                    'price' => ($cart_item->price + $cart_item->options->product_discount) - $this->item_discount[$product_id]
                ]);

            $discount_amount = $this->item_discount[$product_id];

            $this->updateCartOptions($row_id, $product_id, $cart_item, $discount_amount);
        } elseif ($this->discount_type[$product_id] == 'percentage') {
            $discount_amount = ($cart_item->price + $cart_item->options->product_discount) * ($this->item_discount[$product_id] / 100);

            Cart::instance($this->cart_instance)
                ->update($row_id, [
                    'price' => ($cart_item->price + $cart_item->options->product_discount) - $discount_amount
                ]);

            $this->updateCartOptions($row_id, $product_id, $cart_item, $discount_amount);
        }

        session()->flash('discount_message' . $product_id, __('controller.session.flash.discount_added'));
    }

    public function calculate($product) {
        $price = 0;
        $unit_price = 0;
        $product_tax = 0;
        $sub_total = 0;

        if ($product['product_tax_type'] == 1) {
            $price = $product['product_price'] + ($product['product_price'] * ($product['product_order_tax'] / 100));
            $unit_price = $product['product_price'];
            $product_tax = $product['product_price'] * ($product['product_order_tax'] / 100);
            $sub_total = $product['product_price'] + ($product['product_price'] * ($product['product_order_tax'] / 100));
        } elseif ($product['product_tax_type'] == 2) {
            $price = $product['product_price'];
            $unit_price = $product['product_price'] - ($product['product_price'] * ($product['product_order_tax'] / 100));
            $product_tax = $product['product_price'] * ($product['product_order_tax'] / 100);
            $sub_total = $product['product_price'];
        } else {
            $price = $product['product_price'];
            $unit_price = $product['product_price'];
            $product_tax = 0.00;
            $sub_total = $product['product_price'];
        }

        return ['price' => $price, 'unit_price' => $unit_price, 'product_tax' => $product_tax, 'sub_total' => $sub_total];
    }

    public function updateCartOptions($row_id, $product_id, $cart_item, $discount_amount) {
        Cart::instance($this->cart_instance)->update($row_id, ['options' => [
            'sub_total'             => $cart_item->price * $cart_item->qty,
            'code'                  => $cart_item->options->code,
            'stock'                 => $cart_item->options->stock,
            'unit'                 => $cart_item->options->unit,
            'product_tax'           => $cart_item->options->product_tax,
            'unit_price'            => $cart_item->options->unit_price,
            'product_discount'      => $discount_amount,
            'product_discount_type' => $this->discount_type[$product_id],
        ]]);
    }


}
