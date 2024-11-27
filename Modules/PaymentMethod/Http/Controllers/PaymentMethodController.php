<?php

namespace Modules\PaymentMethod\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\businessAmount;
use App\Models\DataConfig;
use App\Models\MasterConfig;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\PaymentGateway\Entities\XenditCreatePayment;
use Modules\PaymentGateway\Entities\XenditInvoiceRequest;
use Modules\PaymentGateway\Entities\XenditPaylaterPlan;
use Modules\PaymentGateway\Entities\XenditPaylaterRequest;
use Modules\PaymentGateway\Entities\xenditPaymentMethod;
use Modules\PaymentGateway\Entities\XenditPaymentRequest;
use Modules\PaymentGateway\Entities\XenditVirtualAccountRequest;
use Modules\PaymentGateway\Http\Controllers\PaymentGatewayController;
use Modules\PaymentMethod\Entities\PaymentChannel;
use Modules\PaymentMethod\Entities\PaymentMethod;
use Modules\Sale\Entities\SelforderCheckout;
use Modules\Sale\Entities\SelforderCheckoutDetail;
use Modules\Sale\Entities\SelforderCheckoutPayment;
use Modules\Sale\Http\Controllers\PosController;
use Str;

class PaymentMethodController extends Controller
{
    public function showChannel(PaymentChannel $channel) {
    $configs = DataConfig::first();
        return view('paymentmethod::index', compact(['channel', 'configs']));
    }
    public function getAllPaymentMethod(Request $request)
    {
        $sourceInfo = $request->source;
        if ($sourceInfo == 'income'){
            $paymentMethod = PaymentMethod::where('status',true)
            ->where('is_income',true)->get();
        }

        if ($sourceInfo == 'selforder'){
            $paymentMethod = PaymentMethod::where('status',true)
            ->where('is_selforder',true)->get();
        }

        if ($sourceInfo == 'sale'){
            $paymentMethod = PaymentMethod::where('status',true)
            ->where('is_sale',true)->get();
        }

        if ($sourceInfo == 'pos'){
            $paymentMethod = PaymentMethod::where('status',true)
            ->where('is_pos',true)->get();
        }

        if ($sourceInfo == 'purchase'){
            $paymentMethod = PaymentMethod::where('status',true)
            ->where('is_purchase',true)->get();
        }


        if ($sourceInfo == 'purchase_return'){
            $paymentMethod = PaymentMethod::where('status',true)
            ->where('is_purchase_return',true)->get();
        }

        if ($sourceInfo == 'sale_return'){
            $paymentMethod = PaymentMethod::where('status',true)
            ->where('is_sale_return',true)->get();
        }

        if ($sourceInfo == 'quotation'){
            $paymentMethod = PaymentMethod::where('status',true)
            ->where('is_quotation',true)->get();
        }

        if ($paymentMethod->count() > 0) {
            return response()->json($paymentMethod);
        } else {
            return response()->json(null);
        }

    }

    public function changePayment(request $request)
    {
        $createPayment = XenditCreatePayment::findOrFail($request->payment_request_id);

        if ( $createPayment){
            if ($createPayment->source_type == "Modules\Sale\Entities\SelforderCheckout"){
                $selforderCheckout = SelforderCheckout::find(id: $createPayment->source_id);
                if ($selforderCheckout){
                    $selforderCheckout->delete();
                }

                $selforderCheckoutDetails = SelforderCheckoutDetail::where('selforder_checkout_id', $createPayment->source_id)
                ->get();

                foreach ($selforderCheckoutDetails as $record) {
                    $record->delete();
                }

                $selforderCheckoutPayments = SelforderCheckoutPayment::where('selforder_checkout_id', $createPayment->source_id)
                ->get();

                foreach ($selforderCheckoutPayments as $record) {
                    $record->delete();
                }
            }

            $businessAmount = businessAmount::query()
            ->where('reference_id',$createPayment->reference_id)
            ->first();

            if ($businessAmount){
                $businessAmount->delete();
            }

            $virtualAccountRequest = XenditVirtualAccountRequest::query()
            ->where('external_id',$createPayment->reference_id)
            ->first();

            if ($virtualAccountRequest){
                $virtualAccountRequest->delete();
            }

            $paymentRequest = XenditPaymentRequest::query()
            ->where('reference_id',$createPayment->reference_id)
            ->first();

            if($paymentRequest){
                $paymentRequest->delete();
            }

            $paymentMethod = XenditPaymentMethod::query()
            ->where('reference_id',$createPayment->reference_id)
            ->first();

            if ($paymentMethod){
                $paymentMethod->delete();
            }

            $invoiceRequest = XenditInvoiceRequest::query()
            ->where('external_id', $createPayment->reference_id)
            ->first();

            if ($invoiceRequest){
                $invoiceRequest->delete();
            }

            $paylaterRequest = XenditPaylaterRequest::query()
            ->where('reference_id',$createPayment->reference_id)
            ->first();

            if ($paylaterRequest){
                $paylaterPlan = XenditPaylaterPlan::find($paylaterRequest->xendit_paylater_plan_id);

                if($paylaterPlan){
                    $paylaterPlan->delete();
                }

                $paylaterRequest->delete();
            }
            $createPayment->delete();
        }


        return response()->json(['result' => 'success'] , 200);

    }

    public function checkPayment(request $request)
    {
        $createPayment = XenditCreatePayment::find($request->payment_request_id);

        return response()->json($createPayment);

    }

    public function getPaymentMethod(string $id)
    {
        $paymentMethod = PaymentMethod::find($id);

        return response()->json($paymentMethod);


    }
    public function getPaymentChannel(string $id)
    {
        $paymentMethod = PaymentChannel::find($id);

        return response()->json($paymentMethod);


    }
    public function getPaymentChannels(Request $request)
    {
        $paymentMethodId = $request->payment_method;
        $paymentChannels = PaymentChannel::where('payment_method_id', $paymentMethodId)->where('status',true)->get();

        if ($paymentChannels->count() > 0) {
            return response()->json($paymentChannels);
        } else {
            return response()->json(null);
        }

    }
    public function getPaymentChannelDetail(Request $request)
    {
        $paymentMethodId = $request->id;
        $paymentChannels = PaymentChannel::find( $paymentMethodId);

        return response()->json([
            'name' => $paymentChannels->name,
            'code' => $paymentChannels->code,
            'type' => $paymentChannels->type,
            'min' => $paymentChannels->min,
            'max' => $paymentChannels->max,
            'action' => $paymentChannels->action,
            'image_url' => $paymentChannels->image_url,
        ]);
    }

    public function index()
    {
        return view('paymentmethod::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('paymentmethod::create');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('paymentmethod::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('paymentmethod::edit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
