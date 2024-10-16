<?php

namespace Modules\PaymentMethod\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\PaymentGateway\Http\Controllers\PaymentGatewayController;
use Modules\PaymentMethod\Entities\PaymentChannel;
use Modules\PaymentMethod\Entities\PaymentMethod;
use Modules\Sale\Http\Controllers\PosController;
use Str;

class PaymentMethodController extends Controller
{

    public function getAllPaymentMethod()
    {
        $paymentMethod = PaymentMethod::where('status',true)->get();

        if ($paymentMethod->count() > 0) {
            return response()->json($paymentMethod);
        } else {
            return response()->json(null);
        }

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
    // public function createPaymentXendit(
    //     string $paymentChannelId,
    //     int $amount){

    //     $paymentChannelData = PaymentChannel::find($paymentChannelId);
    //     $reffPayment =  Str::orderedUuid()::orderedUuid()->toString() . '-' . Carbon::now()->format('Ymdss');

    //     if ($paymentChannelData){
    //         $paymentGatewayController = new PaymentGatewayController();

    //             $paymentResponse = $paymentGatewayController->createPaymentRequest(
    //                 refId: $reffPayment,
    //                 forUserId:null,
    //                 withSplitRule:null,
    //                 amount: $amount * 100,
    //                 type:$paymentChannelData->type,
    //                 channelCode:$paymentChannelData->code,
    //                 reusability:'ONE_TIME_USE'
    //             );

    //             $responseArray = $paymentResponse->getData(true);
    //             $dataResult = $responseArray['data'];

    //     }

    //     return $dataResult;
    // }
    // public function PaymentRequest(Request $request)
    // {
    //     try
    //     {
    //         $posController = new PosController();
    //         // $paymentChannels = PaymentChannel::find($request->payment_channel_id);

    //             if ($request->source == 'xendit'){
    //                 $requestPayment = $posController->createPaymentXendit($request->payment_channel_id, $request->amount);

    //                 if ($request->action == 'account'){
    //                     $valueResponse = 12345789101112;
    //                 }
    //                 else if ($request->action == 'account'){
    //                     $valueResponse = 'some-qr-string';
    //                 }else{
    //                     throw new \Exception('Payment Channel Under Develope');
    //                 }
    //             }
    //         return response()->json(data: [
    //             'reference_id' => $requestPayment['reference_id'] ?? null,
    //             'xendit_create_payment_id' =>  $requestPayment['id'] ?? null,
    //             'value_response' => $valueResponse,
    //             'payment_action' => $request->action,
    //         ]);

    //     }
    //     catch (Exception $e)   {
    //         $result = $e->getMessage();
    //         toast($e->getMessage(), 'error');
    //     }

    //     toast('POS Sale Created!', 'success');

    // }

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
