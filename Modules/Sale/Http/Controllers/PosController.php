<?php

namespace Modules\Sale\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\PaymentMethod\Entities\PaymentChannel;
use Modules\PaymentMethod\Entities\PaymentMethod;
use Modules\People\Entities\Customer;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Entities\SaleDetails;
use Modules\Sale\Entities\SalePayment;
use Modules\Sale\Http\Requests\StorePosSaleRequest;
use Illuminate\Support\Str;
use Modules\PaymentGateway\Entities\xenditCreatePayment;
use Modules\PaymentGateway\Entities\xenditPaymentRequest;
use Modules\PaymentGateway\Http\Controllers\PaymentGatewayController;

class PosController extends Controller
{

    public function index() {
        Cart::instance('sale')->destroy();

        $customers = Customer::where('business_id', Auth::user()->business_id)->get();
        $product_categories = Category::where('business_id', Auth::user()->business_id)->get();

        return view('sale::pos.index', compact('product_categories', 'customers'));
    }

    public function createPaymentXendit(
        string $paymentChannelId,
        int $amount){

        $paymentChannelData = PaymentChannel::find($paymentChannelId);
        $reffPayment =  Str::orderedUuid()->toString() . '-' . Carbon::now()->format('Ymdss');

        if ($paymentChannelData){
            $paymentGatewayController = new PaymentGatewayController();

                $paymentResponse = $paymentGatewayController->createPaymentRequest(
                    refId: $reffPayment,
                    forUserId:null,
                    withSplitRule:null,
                    amount: $amount * 100,
                    type:$paymentChannelData->type,
                    channelCode:$paymentChannelData->code,
                    reusability:'ONE_TIME_USE'
                );

                $responseArray = $paymentResponse->getData(true);
                $dataResult = $responseArray['data'];

        }

        return $dataResult;
    }

    public function store(StorePosSaleRequest $request) {
        try{
            DB::transaction(function () use ($request) {
                $due_amount = $request->total_amount - $request->paid_amount;

                if ($due_amount == $request->total_amount) {
                    $payment_status = 'Unpaid';
                } elseif ($due_amount > 0) {
                    $payment_status = 'Partial';
                } else {
                    $payment_status = 'Paid';
                }

                $sale = Sale::create([
                    'date' => now()->format('Y-m-d'),
                    'reference' => 'PSL',
                    'customer_id' => $request->customer_id,
                    'customer_name' => Customer::find($request->customer_id)->customer_name ?? null,
                    'tax_percentage' => $request->tax_percentage,
                    'discount_percentage' => $request->discount_percentage,
                    'shipping_amount' => $request->shipping_amount * 100,
                    'paid_amount' => $request->paid_amount * 100,
                    'total_amount' => $request->total_amount * 100,
                    'due_amount' => $due_amount * 100,
                    'status' => 'Completed',
                    'payment_status' => $payment_status,
                    'payment_method' => $request->payment_method,
                    'note' => $request->note,
                    'tax_amount' => Cart::instance('sale')->tax() * 100,
                    'discount_amount' => Cart::instance('sale')->discount() * 100,
                    'business_id' => $request->user()->business_id,
                ]);

                foreach (Cart::instance('sale')->content() as $cart_item) {
                    SaleDetails::create([
                        'sale_id' => $sale->id,
                        'product_id' => $cart_item->id,
                        'product_name' => $cart_item->name,
                        'product_code' => $cart_item->options->code,
                        'quantity' => $cart_item->qty,
                        'price' => $cart_item->price * 100,
                        'unit_price' => $cart_item->options->unit_price * 100,
                        'sub_total' => $cart_item->options->sub_total * 100,
                        'product_discount_amount' => $cart_item->options->product_discount * 100,
                        'product_discount_type' => $cart_item->options->product_discount_type,
                        'product_tax_amount' => $cart_item->options->product_tax * 100,
                        'business_id' => $request->user()->business_id,
                    ]);

                    $product = Product::findOrFail($cart_item->id);
                    $product->update([
                        'product_quantity' => $product->product_quantity - $cart_item->qty
                    ]);
                }

                Cart::instance('sale')->destroy();

                if ($sale->paid_amount > 0) {
                    $paymentMethodData = PaymentMethod::find($request->payment_method);
                    $paymentChannelData = PaymentChannel::find($request->payment_channel);

                    //todo nanti ini dipindahkan ke proses Continue Payment
                    if ($paymentChannelData){
                        if ($paymentChannelData->source == 'xendit'){
                            $paymentGatewayResult = $this->createPaymentXendit($request->payment_channel,$sale->paid_amount);
                        }
                    }
                    // dd($paymentGatewayResult);
                    //end Todo
                    SalePayment::create([
                        'date' => now()->format('Y-m-d'),
                        'reference' => 'INV/'.$sale->reference,
                        'reference_id' => $paymentGatewayResult['reference_id'] ?? null,
                        'amount' => $sale->paid_amount,
                        'sale_id' => $sale->id,
                        'payment_method' => $paymentMethodData->id,
                        'payment_method_id' => $paymentMethodData->id,
                        'payment_method_name' => $paymentMethodData->name,
                        'payment_channel_id' => $paymentChannelData->id ?? null,
                        'payment_channel_name' => $paymentChannelData->name ?? null,
                        'business_id' => $request->user()->business_id,
                        'xendit_create_payment_id' => $paymentGatewayResult['id'] ?? null,
                    ]);
                }
            });


        toast('POS Sale Created!', 'success');

        }catch(Exception $e){
            toast($e->getMessage(), 'error');
        }

        return redirect()->route('app.pos.index');

    }
}
