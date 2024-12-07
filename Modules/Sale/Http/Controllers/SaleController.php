<?php

namespace Modules\Sale\Http\Controllers;

use Modules\Sale\DataTables\SalesDataTable;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Modules\PaymentGateway\Entities\XenditCreatePayment;
use Modules\PaymentMethod\Entities\PaymentChannel;
use Modules\PaymentMethod\Entities\PaymentMethod;
use Modules\People\Entities\Customer;
use Modules\Product\Entities\Product;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Entities\SaleDetails;
use Modules\Sale\Entities\SalePayment;
use Modules\Sale\Http\Requests\StoreSaleRequest;
use Modules\Sale\Http\Requests\UpdateSaleRequest;

class SaleController extends Controller
{

    public function index(SalesDataTable $dataTable) {
        abort_if(Gate::denies('access_sales'), 403);

        return $dataTable->render('sale::index');
    }


    public function create() {
        abort_if(Gate::denies('create_sales'), 403);

        Cart::instance('sale')->destroy();

        return view('sale::create');
    }


    public function store(StoreSaleRequest $request) {

        DB::transaction(function () use ($request) {
            $prefix = "INV/";

            if ($request->reference != "SL"){
                $prefix = strtoupper($request->reference)."/";
            }

            $due_amount = $request->total_amount - $request->paid_amount;

            if ($due_amount == $request->total_amount) {
                $payment_status = 'Unpaid';
            } elseif ($due_amount > 0) {
                $payment_status = 'Partial';
            } else {
                $payment_status = 'Paid';
            }

            $paymentMethodData = PaymentMethod::find($request->payment_method);
            if( $paymentMethodData->code == 'INVOICE'){
                $paymentChannelData = PaymentChannel::query()->where('code','INVOICE')->first();
            }
            else{
                $paymentChannelData = PaymentChannel::find($request->payment_channel);
            }
            $paymentChannelName = $paymentMethodData->name;

            $sale = Sale::create([
                'date' => $request->date,
                'customer_id' => $request->customer_id,
                'customer_name' => Customer::find($request->customer_id)->customer_name ?? null,
                'tax_percentage' => $request->tax_percentage,
                'discount_percentage' => $request->discount_percentage,
                'shipping_amount' => $request->shipping_amount,
                'paid_amount' => $request->paid_amount,
                'additional_paid_amount' => 0,
                'total_paid_amount' => $request->paid_amount,
                'total_amount' => $request->total_amount,
                'due_amount' => $due_amount,
                'status' => $request->status,
                'payment_status' => $payment_status,
                'payment_method' => $paymentChannelName,
                'note' => $request->note,
                'tax_amount' => Cart::instance('sale')->tax(),
                'discount_amount' => Cart::instance('sale')->discount(),
                'business_id' => $request->user()->business_id,
            ]);

            foreach (Cart::instance('sale')->content() as $cart_item) {
                SaleDetails::create([
                    'sale_id' => $sale->id,
                    'product_id' => $cart_item->id,
                    'product_name' => $cart_item->name,
                    'product_code' => $cart_item->options->code,
                    'quantity' => $cart_item->qty,
                    'price' => $cart_item->price,
                    'unit_price' => $cart_item->options->unit_price,
                    'sub_total' => $cart_item->options->sub_total,
                    'product_discount_amount' => $cart_item->options->product_discount,
                    'product_discount_type' => $cart_item->options->product_discount_type,
                    'product_tax_amount' => $cart_item->options->product_tax,
                    'business_id' => $request->user()->business_id,
                ]);

                if ($request->status == 'Shipped' || $request->status == 'Completed') {
                    $product = Product::findOrFail($cart_item->id);
                    $product->update([
                        'product_quantity' => $product->product_quantity - $cart_item->qty
                    ]);
                }
            }

            Cart::instance('sale')->destroy();

            if ($sale->paid_amount > 0) {
                SalePayment::create([
                    'date' => $request->date,
                    'reference' => $prefix.$sale->reference,
                    'amount' => $sale->paid_amount,
                    'sale_id' => $sale->id,
                    'business_id' => $request->user()->business_id,
                    'payment_method' => $paymentMethodData->name,
                    'payment_method_id' => $paymentMethodData->id,
                    'payment_method_name' => $paymentMethodData->name,
                    'payment_channel_id' => $paymentChannelData->id ?? null,
                    'payment_channel_name' => $paymentChannelName ?? null,
                ]);
            }
        });
        if ($request->reference != "SL"){
            $prefix = strtoupper($request->reference)."/";
        }
        toast(__('controller.created'), 'success');type:

        if ($request->reference == "SL"){
            return redirect()->route('sales.index');
        }else{
            return redirect()->route('selforder.selforderprocess');
        }

    }

    public function showsale(Sale $sale) {

        $customer = Customer::find($sale->customer_id) ?? null;

        return view('sale::showsale', compact('sale', 'customer'));
    }

    public function show(Sale $sale) {
        abort_if(Gate::denies('show_sales'), 403);

        $customer = Customer::find($sale->customer_id) ?? null;
        $pdf_url = '';
        return view('sale::show', compact('sale', 'customer', 'pdf_url'));
    }

    public function edit(Sale $sale) {
        $paymentChannels = $sale->salePayments;
        foreach($paymentChannels as $paymentChannel){
            if (!blank($paymentChannel->payment_channel_id)){

            toast(__('controller.update_error_online'), 'error');

            return redirect()->route('sales.index');
            }
        }
        abort_if(Gate::denies('edit_sales'), 403);

        $sale_details = $sale->saleDetails;

        Cart::instance('sale')->destroy();

        $cart = Cart::instance('sale');

        foreach ($sale_details as $sale_detail) {
            $cart->add([
                'id'      => $sale_detail->product_id,
                'name'    => $sale_detail->product_name,
                'qty'     => $sale_detail->quantity,
                'price'   => $sale_detail->price,
                'weight'  => 1,
                'options' => [
                    'product_discount' => $sale_detail->product_discount_amount,
                    'product_discount_type' => $sale_detail->product_discount_type,
                    'sub_total'   => $sale_detail->sub_total,
                    'code'        => $sale_detail->product_code,
                    'stock'       => Product::findOrFail($sale_detail->product_id)->product_quantity,
                    'product_tax' => $sale_detail->product_tax_amount,
                    'unit_price'  => $sale_detail->unit_price
                ]
            ]);
        }

        return view('sale::edit', compact('sale'));
    }


    public function update(UpdateSaleRequest $request, Sale $sale) {
        $paymentChannels = $sale->salePayments;
        foreach($paymentChannels as $paymentChannel){
            if (!blank($paymentChannel->payment_channel_id)){

            toast(__('controller.error'), 'Error');

            return redirect()->route('sales.index');
            }
        }
        DB::transaction(function () use ($request, $sale) {


            $due_amount = $request->total_amount - $request->paid_amount;

            if ($due_amount == $request->total_amount) {
                $payment_status = 'Unpaid';
            } elseif ($due_amount > 0) {
                $payment_status = 'Partial';
            } else {
                $payment_status = 'Paid';
            }

            foreach ($sale->saleDetails as $sale_detail) {
                if ($sale->status == 'Shipped' || $sale->status == 'Completed') {
                    $product = Product::findOrFail($sale_detail->product_id);
                    $product->update([
                        'product_quantity' => $product->product_quantity + $sale_detail->quantity
                    ]);
                }
                $sale_detail->delete();
            }

            $paymentMethodData = PaymentMethod::find($request->payment_method);
            $paymentChannelName = $paymentMethodData->name;

            $sale->update([
                'date' => $request->date,
                'reference' => $request->reference,
                'customer_id' => $request->customer_id,
                'customer_name' => Customer::find($request->customer_id)->customer_name ?? null,
                'tax_percentage' => $request->tax_percentage,
                'discount_percentage' => $request->discount_percentage,
                'shipping_amount' => $request->shipping_amount,
                'paid_amount' => $request->paid_amount,
                'total_amount' => $request->total_amount,
                'due_amount' => $due_amount,
                'status' => $request->status,
                'payment_status' => $payment_status,
                'payment_method' => $paymentChannelName,
                'note' => $request->note,
                'tax_amount' => Cart::instance('sale')->tax(),
                'discount_amount' => Cart::instance('sale')->discount(),
            ]);

            foreach (Cart::instance('sale')->content() as $cart_item) {
                SaleDetails::create([
                    'sale_id' => $sale->id,
                    'product_id' => $cart_item->id,
                    'product_name' => $cart_item->name,
                    'product_code' => $cart_item->options->code,
                    'quantity' => $cart_item->qty,
                    'price' => $cart_item->price,
                    'unit_price' => $cart_item->options->unit_price,
                    'sub_total' => $cart_item->options->sub_total,
                    'product_discount_amount' => $cart_item->options->product_discount,
                    'product_discount_type' => $cart_item->options->product_discount_type,
                    'product_tax_amount' => $cart_item->options->product_tax,
                    'business_id' => $request->user()->business_id,
                ]);

                if ($request->status == 'Shipped' || $request->status == 'Completed') {
                    $product = Product::findOrFail($cart_item->id);
                    $product->update([
                        'product_quantity' => $product->product_quantity - $cart_item->qty
                    ]);
                }
            }

            Cart::instance('sale')->destroy();
        });

        toast(__('controller.updated'), 'info');

        return redirect()->route('sales.index');
    }


    public function destroy(Sale $sale) {
        $paymentChannels = $sale->salePayments;
        foreach($paymentChannels as $paymentChannel){
            if (!blank($paymentChannel->payment_channel_id)){

            toast(__('controller.delete_error_online'), 'error');

            return redirect()->route('sales.index');
            }
        }
        abort_if(Gate::denies('delete_sales'), 403);

        $sale->delete();

        toast(__('controller.deleted'), 'warning');

        return redirect()->route('sales.index');
    }
}
