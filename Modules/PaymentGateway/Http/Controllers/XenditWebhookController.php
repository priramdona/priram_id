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
use App\Models\PaymentTransaction;
use App\Models\XenCallback;
use App\Services\PaymentSubscribes\PaymentSubscribeService;
use App\Services\PaymentTransactions\Enums\PaymentTransactionStatusEnum;
use App\Services\Subscribes\SubscribeService\SubscribeService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\PaymentGateway\Entities\xenditCallback;
use Modules\PaymentGateway\Entities\XenditCallbackPaymentRequest;
use Modules\PaymentGateway\Entities\XenditCreatePayment;

class XenditWebhookController extends Controller
{

    public function callbackPaymentMethod(Request $request)
    {
        $data = $request->all();
        try {
            $status =  $data['data']['status'] ?? 'Erorr';

            $resultCallback = XenditCallbackPaymentRequest::create([
                'id' => Str::orderedUuid()->toString(),
                'callback_id' => $data['id'],
                'reference_id' => $data['data']['reference_id'],
                'data' => json_encode($data['data']),
                'event' => $data['event'],
                'status' => $status ,
                'failure_code' => $data['data']['failure_code'],
                'xen_business_id' =>  $data['business_id'] ?? null,
            ]);

            $paymentTransaction = XenditCreatePayment::query()
                ->where('reference_id', $data['data']['reference_id'])
                ->first();

            // if ($data['event'] == 'payment_method.expired') {

            //     if ($paymentTransaction->status != 'SUCCESS') {
            //         $paymentTransaction->status = $status;
            //         $paymentTransaction->save();
            //     }

            //     return response()->json([], 200);

            // } elseif ($data['event'] == 'payment_method.activated') {
            if ($paymentTransaction){
                $paymentTransaction->status = $status;
                $paymentTransaction->save();
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
