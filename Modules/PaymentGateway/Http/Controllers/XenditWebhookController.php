<?php

namespace Modules\PaymentGateway\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UtilityController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events\Payment\ProcessPaymentXenditSuccessEvent;
use App\Http\Controllers\Callbacks\Payments\Enum\EventCaptureEnum;
use App\Http\Controllers\Callbacks\Payments\Enum\EventPaymentEnum;
use App\Http\Controllers\Callbacks\Payments\Enum\EventPaymentMethodEnum;
use App\Http\Controllers\Callbacks\Payments\Enum\PaymentStatusEnum;
use App\Models\businessAmount;
use App\Models\MessageNotification;
use App\Models\PaymentTransaction;
use App\Models\XenCallback;
use App\Services\PaymentSubscribes\PaymentSubscribeService;
use App\Services\PaymentTransactions\Enums\PaymentTransactionStatusEnum;
use App\Services\Subscribes\SubscribeService\SubscribeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Income\Entities\Income;
use Modules\PaymentGateway\Entities\xenditCallback;
use Modules\PaymentGateway\Entities\XenditCallbackPaymentRequest;
use Modules\PaymentGateway\Entities\XenditCreatePayment;
use Modules\PaymentGateway\Entities\XenditCreateVirtualAccount;
use Modules\PaymentGateway\Entities\XenditInvoiceRequest;
use Modules\PaymentGateway\Entities\XenditPaylaterRequest;
use Modules\PaymentGateway\Entities\XenditPaymentMethod;
use Modules\PaymentGateway\Entities\XenditPaymentRequest;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Entities\SalePayment;
use Illuminate\Support\Facades\Http;
use Modules\PaymentGateway\Entities\XenditDisbursement;
use Modules\PaymentGateway\Entities\XenditVirtualAccountRequest;
use Modules\Quotation\Entities\Quotation;
use Modules\Sale\Entities\SelforderCheckout;

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
                        if ($xenditCreatePayments->status != 'Paid') {
                            $xenditCreatePayments->status = $status;
                        }
                    }else{
                        $xenditCreatePayments->status = $status;
                    }

                     //update business_amount
                    $businessAmount = null;
                    $businessAmount = businessAmount::query()
                    ->where('reference_id', $data['data']['reference_id'])
                    ->first();

                    if ($businessAmount){
                        if ($data['event'] == 'payment_method.expired') {
                            if ($businessAmount->status != 'Paid') {
                                $businessAmount->status = $status;
                            }
                        }else{
                            $businessAmount->status = $status;
                        }

                    }else{
                        return response()->json("No Business Found....", 422);
                    }
                    //end of update business_amount

                    //update xendit_Payment_requests
                    $paymentRequest = null;
                    $paymentRequest = XenditPaymentRequest::query()
                    ->where('reference_id', $data['data']['reference_id'])
                    ->first();

                    if ($paymentRequest){
                        if ($data['event'] == 'payment_method.expired') {
                            if ($paymentRequest->status != 'Paid') {
                                $paymentRequest->status = $status;
                            }
                        }else{
                            $paymentRequest->status = $status;
                        }
                    }else{
                        return response()->json("No Payment Request Found....", 422);
                    }
                    //end of update xendit_Payment_requests

                    //update xendit_Payment_method

                    $paymentMethod = null;
                    $paymentMethod = XenditPaymentMethod::query()
                    ->where('reference_id', $data['data']['reference_id'])
                    ->first();

                    if ($paymentMethod){
                        if ($data['event'] == 'payment_method.expired') {
                            if ($paymentMethod->status != 'Paid') {
                                $paymentMethod->status = $status;
                            }
                        }else{
                            $paymentMethod->status = $status;
                        }
                    }else{
                        return response()->json("No Payment Method Found....", 422);
                    }


                    $xenditCreatePayments->save();
                    $businessAmount->save();
                    $paymentRequest->save();
                    $paymentMethod->save();
                }
                else{
                    //payment-methods-callback
                    $this->forwardToProjectB($data, 'payment-methods-callback');
                    return response()->json("No Record Found....", 422);
                }


            return response()->json([], 200);

        } catch (\Exception $exception) {
            // Log::driver('sentry');
            return response()->json([$exception->getMessage()], 422);
        }
    }
    public function callbackPaidPaylater(Request $request)
    {
        $data=[];
        $data = $request->all();
        $sourceType = null;
        $sourceId = null;

        try {
            if ($data['event'] != "paylater.payment"){
                return response()->json("No Invoice Record Found....", 422);
            }

            $refId = $data['data']['reference_id'];
            $xenditPaylaterRequest = XenditPaylaterRequest::query()
            ->where('reference_id', $refId)
            ->first();

                if ($xenditPaylaterRequest){
                    $xenditPaylaterRequest->status = "Paid";
                    $xenditPaylaterRequest->paid_information = json_encode($data);
                    $xenditPaylaterRequest->transaction_timestamp = Carbon::parse($data['created'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');

                    $xenditCreatePayments = XenditCreatePayment::query()
                    ->where('reference_id', $refId)
                    ->first();

                    if ($xenditCreatePayments){
                        $xenditCreatePayments->status = "Paid";
                        $xenditCreatePayments->paid_information = $data;
                        $xenditCreatePayments->transaction_timestamp = Carbon::parse($data['created'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');

                            //update business_amount
                            $businessAmount = null;
                            $businessAmount = businessAmount::query()
                            ->where('reference_id', $refId)
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
                                if ($sourceTransaction){
                                    $sourceType = "sales";
                                    $sourceId = $sourceTransaction->id;
                                    $sourceTransaction->payment_status = "Paid";
                                }else{
                                    return response()->json("No Source Found....", 422);
                                }
                            }

                            if ($xenditCreatePayments->source_type == 'Modules\Quotation\Entities\Quotation'){
                                $sourceTransaction = Quotation::find($xenditCreatePayments->source_id);
                                if ($sourceTransaction){
                                    $sourceType = "quotations";
                                    $sourceId = $sourceTransaction->id;
                                    $sourceTransaction->invoice_status = "Paid";
                                }else{
                                    return response()->json("No Source Found....", 422);
                                }
                            }

                            if ($xenditCreatePayments->source_type == 'Modules\Sale\Entities\SelforderCheckout'){
                                $sourceTransaction = SelforderCheckout::find($xenditCreatePayments->source_id);

                                if ($sourceTransaction){
                                    $sourceType = "Selforder";
                                    $sourceId = $sourceTransaction->id;
                                    $sourceTransaction->payment_status = "Paid";
                                }else{
                                    return response()->json("No Source Found....", 422);
                                }
                            }

                            $messageNotifications = new UtilityController;
                            $messageNotifications->insertMessageNotifications(
                                'Pembayaran Paylater Berhasil.',
                                'Yeay Pembayaran Paylater telah berhasil untuk Transaksi ' . $sourceTransaction->reference .'. Saldo akan bertambah jika proses Settlement sudah diterima. (Baca panduan di Pembayaran Online), Terima Kasih!.... ',
                                $sourceType,$sourceId
                            );


                    }
                    else{
                        return response()->json("No Record Found....", 422);
                    }

                }else{
                    return response()->json("No Invoice Record Found....", 422);
                }

                $xenditPaylaterRequest->save();
                $xenditCreatePayments->save();
                $businessAmount->save();
                $sourceTransaction->save();

                return response()->json([], 200);

        } catch (\Exception $exception) {
            // Log::driver('sentry');
            return response()->json([$exception->getMessage()], 422);
        }
    }
    public function callbackPaidInvoice(Request $request)
    {
        $data=[];
        $data = $request->all();

        $sourceType = null;
        $sourceId = null;

        try {
            $refId = $data['external_id'];
            $xenditInvoiceRequest = XenditInvoiceRequest::query()
            ->where('external_id', $refId)
            ->first();

                if ($xenditInvoiceRequest){
                    $xenditInvoiceRequest->status = "Paid";
                    $xenditInvoiceRequest->paid_information = json_encode($data);
                    $xenditInvoiceRequest->transaction_timestamp = Carbon::parse($data['paid_at'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');

                    $xenditCreatePayments = XenditCreatePayment::query()
                    ->where('reference_id', $refId)
                    ->first();

                    if ($xenditCreatePayments){
                        $xenditCreatePayments->status = "Paid";
                        $xenditCreatePayments->paid_information = $data;
                        $xenditCreatePayments->transaction_timestamp = Carbon::parse($data['paid_at'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');

                            //update business_amount
                            $businessAmount = null;
                            $businessAmount = businessAmount::query()
                            ->where('reference_id', $refId)
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
                                if ($sourceTransaction){

                                    $sourceType = "sales";
                                    $sourceId = $sourceTransaction->id;
                                    $sourceTransaction->payment_status = "Paid";
                                }else{
                                    return response()->json("No Source Found....", 422);
                                }
                            }

                            if ($xenditCreatePayments->source_type == 'Modules\Quotation\Entities\Quotation'){
                                $sourceTransaction = Quotation::find($xenditCreatePayments->source_id);
                                if ($sourceTransaction){
                                    $sourceType = "quotations";
                                    $sourceId = $sourceTransaction->id;
                                    $sourceTransaction->invoice_status = "Paid";
                                }else{
                                    return response()->json("No Source Found....", 422);
                                }
                            }

                            if ($xenditCreatePayments->source_type == 'Modules\Income\Entities\Income'){
                                $sourceTransaction = Income::find($xenditCreatePayments->source_id);
                                if ($sourceTransaction){
                                    $sourceType = "incomes";
                                    $sourceId = $sourceTransaction->id;
                                    $sourceTransaction->paymet_status = "Paid";
                                }else{
                                    return response()->json("No Source Found....", 422);
                                }
                            }

                            if ($xenditCreatePayments->source_type == 'Modules\Sale\Entities\SelforderCheckout'){
                                $sourceTransaction = SelforderCheckout::find($xenditCreatePayments->source_id);

                                if ($sourceTransaction){
                                    $sourceType = "Selforder";
                                    $sourceId = $sourceTransaction->id;
                                    $sourceTransaction->payment_status = "Paid";
                                }else{
                                    return response()->json("No Source Found....", 422);
                                }
                            }


                            $messageNotifications = new UtilityController;
                            $messageNotifications->insertMessageNotifications(
                                'Pembayaran Faktur Tagihan Berhasil.',
                                'Yeay Pembayaran Faktur Tagihan Telah berhasil untuk Transaksi ' . $sourceTransaction->reference .'. Saldo akan bertambah jika proses Settlement sudah diterima. (Baca panduan di Pembayaran Online), Terima Kasih!.... ',
                                $sourceType,$sourceId
                            );
                    }
                    else{
                        return response()->json("No Record Found....", 422);
                    }

                }else{
                    return response()->json("No Invoice Record Found....", 422);
                }

                $xenditInvoiceRequest->save();
                $xenditCreatePayments->save();
                $businessAmount->save();
                $sourceTransaction->save();


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
        $sourceType = null;
        $sourceId = null;

        try {

            $xenditVirtualAccountRequest = XenditVirtualAccountRequest::query()
                ->where('external_id', $data['external_id'])
                ->first();

                if ($xenditVirtualAccountRequest){
                    $xenditVirtualAccountRequest->status = "Paid";
                    $xenditVirtualAccountRequest->paid_information = json_encode($data);
                    $xenditVirtualAccountRequest->transaction_timestamp = Carbon::parse($data['transaction_timestamp'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');

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

                                if ($sourceTransaction){
                                    $sourceType = "sales";
                                    $sourceId = $sourceTransaction->id;
                                    $sourceTransaction->payment_status = "Paid";
                                }else{
                                    return response()->json("No Source Found....", 422);
                                }
                            }

                            if ($xenditCreatePayments->source_type == 'Modules\Income\Entities\Income'){
                                $sourceTransaction = Income::find($xenditCreatePayments->source_id);

                                if ($sourceTransaction){
                                    $sourceType = "incomes";
                                    $sourceId = $sourceTransaction->id;
                                    $sourceTransaction->payment_status = "Paid";
                                }else{
                                    return response()->json("No Source Found....", 422);
                                }
                            }

                            if ($xenditCreatePayments->source_type == 'Modules\Sale\Entities\SelforderCheckout'){
                                $sourceTransaction = SelforderCheckout::find($xenditCreatePayments->source_id);

                                if ($sourceTransaction){
                                    $sourceType = "Selforder";
                                    $sourceId = $sourceTransaction->id;
                                    $sourceTransaction->payment_status = "Paid";
                                }else{
                                    return response()->json("No Source Found....", 422);
                                }
                            }


                        $messageNotifications = new UtilityController;
                        $messageNotifications->insertMessageNotifications(
                            'Pembayaran Virtual Account Berhasil.',
                            'Yeay Pembayaran Virtual Account telah berhasil untuk Transaksi ' . $sourceTransaction->reference .'. Saldo akan bertambah jika proses Settlement sudah diterima. (Baca panduan di Pembayaran Online), Terima Kasih!.... ',
                            $sourceType,$sourceId
                        );

                    }
                    else{
                        return response()->json("No Record Found....", 422);
                    }

                    $xenditVirtualAccountRequest->save();
                    $xenditCreatePayments->save();
                    $businessAmount->save();
                    $sourceTransaction->save();

                }else{
                    $this->forwardToProjectB($data, 'va-paid');
                    return response()->json("No Virtual Record Found....", 422);
                }


                return response()->json([], 200);

        } catch (\Exception $exception) {
            // Log::driver('sentry');
            return response()->json([$exception->getMessage()], 422);
        }
    }
    public function disbursments(Request $request)
    {
        $data = $request->all();
        $status = null;
        $statusMsg = null;
        // $failureCode = null;
        try {
            if ($data['event'] == 'payout.succeeded') {
                $status = "Success";
                $statusMsg = "Diajukan";
            }else{
                $status = $data['data']['status'];
                $statusMsg = $data['data']['status'];
            }

            $xenditDisbursement = XenditDisbursement::query()
                ->where('reference_id', $data['data']['reference_id'])
                ->first();

                if ($xenditDisbursement){
                    $xenditDisbursement->status = $status;
                    $xenditDisbursement->estimated_arrival_time = Carbon::parse($data['data']['estimated_arrival_time'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');

                    $businessAmount = businessAmount::query()
                    ->where('reference_id', $data['data']['reference_id'])
                    ->first();

                    if ($businessAmount){
                        $businessAmount->status = $status;
                        $businessAmount->save();
                        $trxDate = Carbon::parse($businessAmount->created_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');

                        $messageNotifications = new UtilityController;
                        $messageNotifications->insertMessageNotifications(
                            'Penarikan Dana ' . $trxDate . '.',
                            'Penarikan Dana pada Tanggal : ' . $trxDate .' sebesar ' . format_currency($businessAmount->transaction_amount) . 'Telah sukses Diproses, Estimasi akan diterima pada : ' . $xenditDisbursement->estimated_arrival_time . ' Saat ini status penarikan Dana anda : ' . $statusMsg . ', Cek berkala dan pastikan Dana diterima dengan Baik.',
                        'disbursements',$xenditDisbursement->id
                );
                    }
                    $xenditDisbursement->save();



                }else{
                    return response()->json("No Disbursement Record Found....", 422);
                }



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
                    //create-va-callback
                    $this->forwardToProjectB($data, 'create-va-callback');
                    // return response()->json("No Virtual Record Found....", 422);
                }

                return response()->json([], 200);

        } catch (\Exception $exception) {
            // Log::driver('sentry');
            return response()->json([$exception->getMessage()], 422);
        }
    }
    public function callbackPaymentSucceeded(Request $request)
    {
        $data = [];
        $data = $request->all();
        $sourceType = null;
        $sourceId = null;

        try {

            if ($data['event'] == 'payment.succeeded') {
                $referenceId = $data['data']['payment_method']['reference_id'] ?? null;

                if (!$referenceId){
                    return response()->json("Invalid reference_id....", 422);
                }

                $xenditCreatePaymentRequest = XenditPaymentRequest::query()
                ->where('reference_id', $referenceId)
                ->first();

                if ($xenditCreatePaymentRequest){
                    $xenditCreatePaymentRequest->status = "Paid";
                    $xenditCreatePaymentRequest->paid_information = json_encode($data);
                    $xenditCreatePaymentRequest->transaction_timestamp = Carbon::parse($data['created'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');

                        $xenditCallbackPaymentRequest = XenditCallbackPaymentRequest::query()
                        ->where('reference_id', $referenceId)
                        ->first();

                        if ($xenditCallbackPaymentRequest){
                            $xenditCallbackPaymentRequest->status = "Paid";
                        }else{
                            return response()->json("Callback Payment Request Record not Found....", 422);
                        }

                        $xenditPaymentMethod = XenditPaymentMethod::query()
                            ->where('reference_id', $referenceId)
                            ->first();

                        if ($xenditPaymentMethod){
                            $xenditPaymentMethod->status = "Paid";
                        }else{
                            return response()->json("Payment Method Record not Found....", 422);
                        }

                        $xenditCreatePayments = XenditCreatePayment::query()
                            ->where('reference_id', $referenceId)
                            ->first();

                        if ($xenditCreatePayments){
                            $xenditCreatePayments->status = "Paid";
                        }else{
                            return response()->json("Create Payment Record not Found....", 422);
                        }

                         //update business_amount
                            $businessAmount = null;
                            $businessAmount = businessAmount::query()
                            ->where('reference_id', $referenceId)
                            ->first();

                            if ($businessAmount){
                                $businessAmount->status = 'Paid';
                            }else{
                                return response()->json("No Business Found....", 422);
                            }
                        //end of update business_amount

                            $sourceTransaction = null;
                            if ($xenditCreatePayments->source_type == 'Modules\Sale\Entities\Sale'){
                                $sourceTransaction = Sale::find($xenditCreatePayments->source_id);

                                if ($sourceTransaction){
                                    $sourceType = "sales";
                                    $sourceId = $sourceTransaction->id;
                                    $sourceTransaction->payment_status = "Paid";
                                }else{
                                    return response()->json("No Source Found....", 422);
                                }
                            }

                            if ($xenditCreatePayments->source_type == 'Modules\Income\Entities\Income'){
                                $sourceTransaction = Income::find($xenditCreatePayments->source_id);

                                if ($sourceTransaction){
                                    $sourceType = "incomes";
                                    $sourceId = $sourceTransaction->id;
                                    $sourceTransaction->paymet_status = "Paid";
                                }else{
                                    return response()->json("No Source Found....", 422);
                                }
                            }

                            if ($xenditCreatePayments->source_type == 'Modules\Sale\Entities\SelforderCheckout'){
                                $sourceTransaction = SelforderCheckout::find($xenditCreatePayments->source_id);

                                if ($sourceTransaction){
                                    $sourceType = "Selforder";
                                    $sourceId = $sourceTransaction->id;
                                    $sourceTransaction->payment_status = "Paid";
                                }else{
                                    return response()->json("No Source Found....", 422);
                                }
                            }

                        $messageNotifications = new UtilityController;
                        $messageNotifications->insertMessageNotifications(
                            'Pembayaran Online Berhasil.',
                            'Yeay Pembayaran telah berhasil untuk Transaksi ' . $sourceTransaction->reference .'. Saldo akan bertambah jika proses Settlement sudah diterima. (Baca panduan di Pembayaran Online), Terima Kasih!.... ',
                            $sourceType,$sourceId
                        );

                    $xenditCreatePaymentRequest->save();
                    $xenditCallbackPaymentRequest->save();
                    $xenditPaymentMethod->save();
                    $xenditCreatePayments->save();
                    $businessAmount->save();
                    $sourceTransaction->save();

                }else{
                         // Kirim ke Project B jika data tidak ditemukan
                    $this->forwardToProjectB($data, 'payment-methods-succeeded');
                    // return response()->json("Payment Request Record not Found....", 422);
                }
            }else{
                return response()->json("Event status invalid....", 422);
            }

            return response()->json([], 200);


        } catch (\Exception $exception) {
            // Log::driver('sentry');
            return response()->json([$exception->getMessage()], 422);
        }
    }

    private function forwardToProjectB($data, $method)
    {
        try {
            $url = "https://www.donasikita.com/payment-gateways/{$method}";

            $response = Http::post($url, $data);

            if ($response->successful()) {
                return response()->json([], 200);
            }

            return response()->json("Payment Request Record not Found....", 422);
        } catch (\Exception $e) {
            return response()->json("Error process", 422);
        }
    }

}
