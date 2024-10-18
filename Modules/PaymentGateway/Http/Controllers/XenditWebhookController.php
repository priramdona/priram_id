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
use Modules\PaymentGateway\Entities\xenditCreatePayment;

class XenditWebhookController extends Controller
{

    public function callback(Request $request)
    {
        $data = $request->all();
        try {

            xenditCallback::create([
                'id' => Str::orderedUuid()->toString(),
                'payment_transaction_id' => $data['data']['reference_id'],
                'event' => $data['event'],
                'status' => $data['data']['status'],
                'failure_code' => $data['data']['failure_code'],
            ]);

            if ($data['event'] == 'payment_method.expired') {
                $paymentTransaction = xenditCreatePayment::query()
                    ->where('reference_id', $data['data']['reference_id'])
                    ->first();

                if ($paymentTransaction->status != 'SUCCESS') {
                    $paymentTransaction->status = 'EXPIRED';
                    $paymentTransaction->save();
                }

                return response()->json([], 200);

            } elseif ($data['event'] == 'payment_method.activated') {

                return response()->json([], 200);
            }

            if ($data['event'] == 'payment.succeeded') {
                if ($data['data']['status'] == 'SUCCEEDED') {
                    // event(new ProcessPaymentXenditSuccessEvent($data));

                    return response()->json([], 200);
                }
            } elseif ($data['event'] =='payment.failed') {
                $paymentTransaction = xenditCreatePayment::query()
                    ->where('reference_id', $data['data']['reference_id'])
                    ->first();

                if ($data['data']['status'] == 'FAILED') {
                    $paymentTransaction->status = 'FAILED';
                    $paymentTransaction->save();
                }

                // \DB::commit();
                return response()->json([], 200);
            }

            if ($data['event'] == 'payment.succeeded') {
                if ($data['data']['status'] == 'SUCCEEDED') {

                    // event(new ProcessPaymentXenditSuccessEvent($data));

                    return response()->json([], 200);
                }
            } elseif ($data['event'] == 'payment.failed') {
                $paymentTransaction = xenditCreatePayment::query()
                    ->where('id', $data['data']['reference_id'])
                    ->first();

                if ($data['data']['status'] == 'FAILED') {
                    $paymentTransaction->status = 'FAILED';
                    $paymentTransaction->save();
                }
            }

            // \DB::commit();
            return response()->json([], 200);
        } catch (\Exception $exception) {
            // Log::driver('sentry');
            return response()->json([], 422);
        }
    }
}
