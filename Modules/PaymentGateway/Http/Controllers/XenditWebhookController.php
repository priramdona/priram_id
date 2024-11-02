<?php

namespace Modules\PaymentGateway\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events\Payment\ProcessPaymentXenditSuccessEvent;
use App\Http\Controllers\Callbacks\Payments\Enum\EventCaptureEnum;
use App\Http\Controllers\Callbacks\Payments\Enum\EventPaymentEnum;
use App\Http\Controllers\Callbacks\Payments\Enum\EventPaymentMethodEnum;
use App\Http\Controllers\Callbacks\Payments\Enum\PaymentStatusEnum;
use App\Models\businessAmount;
use App\Models\PaymentTransaction;
use App\Models\XenCallback;
use App\Services\PaymentSubscribes\PaymentSubscribeService;
use App\Services\PaymentTransactions\Enums\PaymentTransactionStatusEnum;
use App\Services\Subscribes\SubscribeService\SubscribeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\PaymentGateway\Entities\xenditCallback;
use Modules\PaymentGateway\Entities\XenditCallbackPaymentRequest;
use Modules\PaymentGateway\Entities\XenditCreatePayment;
use Modules\PaymentGateway\Entities\XenditCreateVirtualAccount;
use Modules\PaymentGateway\Entities\XenditPaymentMethod;
use Modules\PaymentGateway\Entities\XenditPaymentRequest;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Entities\SalePayment;
use Illuminate\Support\Facades\Http;
use Modules\PaymentGateway\Entities\XenditVirtualAccountRequest;

class XenditWebhookController extends Controller
{
    public function callbackPaymentMethod(Request $request)
    {
        $data = $request->all();
        try {
            $status =  $data['data']['status'] ?? 'Erorr';

            XenditCallbackPaymentRequest::create([
                'id' => Str::orderedUuid()->toString(),
                'callback_id' => $data['id'],
                'reference_id' => $data['data']['reference_id'],
                'data' => json_encode($data['data']),
                'event' => $data['event'],
                'status' => $status ,
                'failure_code' => $data['data']['failure_code'],
                'xen_business_id' =>  $data['business_id'] ?? null,
            ]);

            $xenditCreatePayments = XenditCreatePayment::query()
                ->where('reference_id', $data['data']['reference_id'])
                ->first();

                if ($xenditCreatePayments){
                    if ($data['event'] == 'payment_method.expired') {
                        if ($xenditCreatePayments->status != 'SUCCEEDED') {
                            $xenditCreatePayments->status = $status;
                            $xenditCreatePayments->save();
                        }
                    }else{
                        $xenditCreatePayments->status = $status;
                        $xenditCreatePayments->save();
                    }

                    $sourceTransaction = null;
                    //update Source Transactions
                    if ($xenditCreatePayments->source_type == 'Modules\Sale\Entities\Sale'){
                        $sourceTransaction = Sale::find($xenditCreatePayments->source_id);
                    }

                    if ($sourceTransaction){
                        $sourceTransaction->payment_status = $status;
                        $sourceTransaction->save();
                    }
                    //end of Update Source Transactions

                     //update business_amount
                    $businessAmount = null;
                    $businessAmount = businessAmount::query()
                    ->where('reference_id', $data['data']['reference_id'])
                    ->first();

                    if ($businessAmount){
                        $businessAmount->status = $status;
                        $businessAmount->save();
                    }
                    //end of update business_amount

                    //update xendit_Payment_requests
                    $paymentRequest = null;
                    $paymentRequest = XenditPaymentRequest::query()
                    ->where('reference_id', $data['data']['reference_id'])
                    ->first();

                    if ($paymentRequest){
                        $paymentRequest->status = $status;
                        $paymentRequest->save();
                    }
                    //end of update xendit_Payment_requests

                    //update xendit_Payment_method

                    $paymentMethod = null;
                    $paymentMethod = XenditPaymentMethod::query()
                    ->where('reference_id', $data['data']['reference_id'])
                    ->first();

                    if ($paymentMethod){
                        $paymentMethod->status = $status;
                        $paymentMethod->save();
                    }
                    //end of Update xendit_Payment_method
                }
                else{
                    return response()->json("No Record Found....", 422);
                }

            return response()->json([], 200);

        } catch (\Exception $exception) {
            // Log::driver('sentry');
            return response()->json([$exception->getMessage()], 422);
        }
    }
    public function callbackPaidVirtualAccount(Request $request)
    {
        $data=[];
        $data = $request->all();

        try {

            $xenditVirtualAccountRequest = XenditVirtualAccountRequest::query()
                ->where('external_id', $data['external_id'])
                ->first();

                if ($xenditVirtualAccountRequest){
                    $xenditVirtualAccountRequest->status = "Paid";
                    $xenditVirtualAccountRequest->paid_information = json_encode($data);
                    $xenditVirtualAccountRequest->transaction_timestamp = Carbon::parse($data['transaction_timestamp'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
                    $xenditVirtualAccountRequest->save();


                    $xenditCreatePayments = XenditCreatePayment::query()
                    ->where('reference_id', $data['external_id'])
                    ->first();

                    if ($xenditCreatePayments){
                            $xenditCreatePayments->status = "Paid";

                            //update business_amount
                            $businessAmount = null;
                            $businessAmount = businessAmount::query()
                            ->where('reference_id', $data['external_id'])
                            ->first();

                            if ($businessAmount){
                                $businessAmount->status = 'Paid';
                            }else{
                                return response()->json("No Business Found....", 422);
                            }
                            //end of update business_amount


                    //update Source Transactions
                            $sourceTransaction = null;
                            if ($xenditCreatePayments->source_type == 'Modules\Sale\Entities\Sale'){
                                $sourceTransaction = Sale::find($xenditCreatePayments->source_id);
                            }

                            if ($sourceTransaction){
                                $sourceTransaction->payment_status = "Paid";
                            }else{
                                return response()->json("No Source Found....", 422);
                            }
                    }
                    else{
                        return response()->json("No Record Found....", 422);
                    }

                }else{
                    return response()->json("No Virtual Record Found....", 422);
                }


                $xenditCreatePayments->save();
                $businessAmount->save();
                $sourceTransaction->save();

                return response()->json([], 200);

        } catch (\Exception $exception) {
            // Log::driver('sentry');
            return response()->json([$exception->getMessage()], 422);
        }
    }
    public function callbackCreateVirtualAccount(Request $request)
    {
        $data = $request->all();
        try {
            $status =  $data['status'] ?? 'Erorr';

            $xenditCreatePayments = XenditVirtualAccountRequest::query()
                ->where('external_id', $data['external_id'])
                ->first();

                if ($xenditCreatePayments){
                    $xenditCreatePayments->status = $status;
                    $xenditCreatePayments->created = Carbon::parse($data['created'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
                    $xenditCreatePayments->updated = Carbon::parse($data['updated'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
                    $xenditCreatePayments->expected_amount = $data['expected_amount'];
                    $xenditCreatePayments->save();

                }else{
                    return response()->json("No Virtual Record Found....", 422);
                }

                return response()->json([], 200);

        } catch (\Exception $exception) {
            // Log::driver('sentry');
            return response()->json([$exception->getMessage()], 422);
        }
    }
    public function callbackPaymentSucceeded(Request $request)
    {
        $data = $request->all();
        try {
            $status =  $data['data']['status'] ?? 'Erorr';

            XenditCallbackPaymentRequest::create([
                'id' => Str::orderedUuid()->toString(),
                'callback_id' => $data['id'],
                'reference_id' => $data['data']['reference_id'],
                'data' => json_encode($data['data']),
                'event' => $data['event'],
                'status' => $status ,
                'failure_code' => $data['data']['failure_code'],
                'xen_business_id' =>  $data['business_id'] ?? null,
            ]);

            $xenditCreatePayments = XenditCreatePayment::query()
                ->where('reference_id', $data['data']['reference_id'])
                ->first();

            if ($xenditCreatePayments){

                if ($data['event'] == 'payment_method.expired') {
                    if ($xenditCreatePayments->status != 'SUCCEEDED') {
                        $xenditCreatePayments->status = $status;
                        $xenditCreatePayments->save();
                    }
                }else{
                    $xenditCreatePayments->status = $status;
                    $xenditCreatePayments->save();
                }

                if ($xenditCreatePayments->source_type == 'Modules\Sale\Entities\Sale'){
                    $sourceTransaction = Sale::find($xenditCreatePayments->source_id);
                }

                if ($sourceTransaction){
                    $sourceTransaction->payment_status = $status;
                    $sourceTransaction->save();
                }

            }

                return response()->json([], 200);





            // }



            // if ($data['event'] == 'payment_method.expired') {
            //     $paymentTransaction = XenditCallbackPaymentRequest::query()
            //         ->where('reference_id', $data['data']['reference_id'])
            //         ->first();

            //     if ($paymentTransaction->status != 'SUCCESS') {
            //         $paymentTransaction->status = 'EXPIRED';
            //         $paymentTransaction->save();
            //     }

            //     return response()->json([], 200);

            // } elseif ($data['event'] == 'payment_method.activated') {

            //     return response()->json([], 200);
            // }

            // if ($data['event'] == 'payment.succeeded') {
            //     if ($data['data']['status'] == 'SUCCEEDED') {
            //         // event(new ProcessPaymentXenditSuccessEvent($data));

            //         return response()->json([], 200);
            //     }
            // } elseif ($data['event'] =='payment.failed') {
            //     $paymentTransaction = xenditCreatePayment::query()
            //         ->where('reference_id', $data['data']['reference_id'])
            //         ->first();

            //     if ($data['data']['status'] == 'FAILED') {
            //         $paymentTransaction->status = 'FAILED';
            //         $paymentTransaction->save();
            //     }

            //     // \DB::commit();
            //     return response()->json([], 200);
            // }

            // if ($data['event'] == 'payment.succeeded') {
            //     if ($data['data']['status'] == 'SUCCEEDED') {

            //         // event(new ProcessPaymentXenditSuccessEvent($data));

            //         return response()->json([], 200);
            //     }
            // } elseif ($data['event'] == 'payment.failed') {
            //     $paymentTransaction = xenditCreatePayment::query()
            //         ->where('id', $data['data']['reference_id'])
            //         ->first();

            //     if ($data['data']['status'] == 'FAILED') {
            //         $paymentTransaction->status = 'FAILED';
            //         $paymentTransaction->save();
            //     }
            // }

            // \DB::commit();
            // return response()->json([], 200);
        } catch (\Exception $exception) {
            // Log::driver('sentry');
            return response()->json([$exception->getMessage()], 422);
        }
    }
}
