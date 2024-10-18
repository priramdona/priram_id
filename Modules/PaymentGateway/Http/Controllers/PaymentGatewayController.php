<?php

namespace Modules\PaymentGateway\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\businessAmount;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\PaymentGateway\Entities\xenditPaymentMethod;
use Modules\PaymentGateway\Entities\xenditPaymentRequest;
use Xendit\Configuration;
use Xendit\PaymentMethod\PaymentMethodApi;
use Illuminate\Support\Facades\Http;
use Modules\PaymentMethod\Entities\PaymentChannel;
use Xendit\BalanceAndTransaction\BalanceApi;
use Xendit\PaymentRequest\PaymentRequestApi;
use Xendit\PaymentRequest\PaymentRequestParameters;

class PaymentGatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        try {
           $result = PaymentChannel::where('status',true)->get();


        } catch (\Exception $e) {
            return $e;
        }

        return view('paymentgateway::index',compact('result'));
    }
    public function setting()
    {

        try {
           $result = PaymentChannel::where('status',true)->get();


        } catch (\Exception $e) {
            return $e;
        }

        return view('paymentgateway::layouts.settings',compact('result'));
    }
    public function createPaymentRequest(string $refId,
                                        ?string $forUserId = null,
                                        ?string $withSplitRule = null,
                                        int $amount,
                                        int $saleAmount,
                                        string $type,
                                        string $channelCode,
                                        ?string $reusability = 'ONE_TIME_USE',
                                        ?string $phoneNumber = null,
                                        ?array $basket = null,
                                        ?array $metadata = null){

        Configuration::setXenditKey(env('XENDIT_KEY'));
        $apiInstance = new PaymentRequestApi();
        $idempotency_key = rand(1,10000) . Carbon::now()->format('Ymmddss');
        $paymentMethod = null;
        $channelProperties = null;
        $getBusinessData = Business::find(Auth::user()->business_id);
        $payloadType = null;

        if ($type === 'EWALLET'){
            if ($channelCode == 'OVO'){
                $channelProperties = [
                    'mobile_number' => '62'.$phoneNumber,
                    'expires_at' => Carbon::now()->addMinutes(60)->toIso8601String(),
                ];
            }
            else if ($channelCode == 'DANA' || $channelCode == 'LINKAJA' || $channelCode == 'SHOPEEPAY'  ){
                $channelProperties = [
                    'success_return_url' => 'https://redirect.me/success',
                ];
            }
            else{
                $channelProperties = [
                    'success_return_url' => 'https://redirect.me/success',
                    'failure_return_url' => 'https://redirect.me/failure',
                ];
            }

            $payloadType = [
                'channel_code' => $channelCode,
                'channel_properties' => $channelProperties,
            ];

            $paymentMethod = [
                'type' => $type,
                'reusability' => $reusability,
                'ewallet' => $payloadType,
            ];
        }else if ($type === 'QR_CODE'){
            $paymentMethod =  [
                'type' => $type,
                'reusability' => $reusability,
                'qr_code' => [
                  'channel_code' => $channelCode
                ]
              ];
        }else if ($type === 'VIRTUAL_ACCOUNT'){
            $paymentMethod = [
                'type' => $type,
                'reusability' => $reusability,
                'reference_id' => $refId,
                'virtual_account' => [
                        'channel_code' => $channelCode,
                        'channel_properties' => [
                        'customer_name' => preg_replace('/[^a-zA-Z\s]/','', $getBusinessData->name),
                        'expires_at' =>  Carbon::now()->addDays(1)->toIso8601String(),
                        // 'virtual_account_number' => '8860810000000'
                    ]
                ]
                ];
        }else{
            throw new \Exception('Payment Channel not Exist in Payment Gateway');
        }

        $payloadRequest = [
          'reference_id' => $refId,
          'amount' => $amount,
          'currency' => 'IDR',
          'country' => 'ID',
          'basket' => $basket,
          'metadata' => $metadata,
          'payment_method' => $paymentMethod
        ];

        $paymentRequestParameters = new PaymentRequestParameters($payloadRequest);

        try {
            $dataResult = $apiInstance->createPaymentRequest($idempotency_key, $forUserId, $withSplitRule, $paymentRequestParameters);
            $resultDetails = null;
            $dataPaymentMethods = json_decode($dataResult['payment_method'],true);
            // dd($dataPaymentMethods);

            $xenditPaymentRequestResponsePayload = [
                'payment_request_id'=> $dataResult['id'],
                'created'=> Carbon::parse($dataResult['created'])->format('Y-m-d H:i:s'),  // Konversi ke format MySQL
                'updated'=> Carbon::parse($dataResult['updated'])->format('Y-m-d H:i:s'),
                'reference_id'=> $dataResult['reference_id'],
                'business_id'=> $dataResult['business_id'],
                'customer_id'=> $dataResult['customer_id'],
                'customer'=> ($dataResult['customer']),
                'amount'=> $dataResult['amount'],
                'min_amount'=> $dataResult['min_amount'] ?? 0,
                'max_amount'=> $dataResult['max_amount'] ?? 0,
                'country'=> $dataResult['country'],
                'currency'=> $dataResult['currency'],
                'payment_method'=> json_encode($dataResult['payment_method']),
                'description'=> $dataResult['description'],
                'failure_code'=> $dataResult['failure_code'],
                'capture_method'=> $dataResult['capture_method'],
                'initiator'=> $dataResult['initiator'],
                'card_verification_results'=> $dataResult['card_verification_results'],
                'status'=> $dataResult['status'],
                'actions'=> json_encode($dataResult['actions']),
                'metadata'=> json_encode($dataResult['metadata']),
                'shipping_information'=> json_encode($dataResult['shipping_information']),
                'items'=> json_encode($dataResult['items']),
            ];

            $xenditPaymentRequest = xenditPaymentRequest::create($xenditPaymentRequestResponsePayload);

            $payloadPaymentMethod =[
                'pm_id' => $dataPaymentMethods['id'] ?? null,
                'business_id' => $dataPaymentMethods['business_id'] ?? null,
                'customer_id' => $dataPaymentMethods['customer_id'] ?? null,
                'xendit_payment_request_id' => $xenditPaymentRequest['id'] ?? null,
                'type' => $dataPaymentMethods['type'] ?? null,
                'country' => $dataPaymentMethods['country'] ?? null,
                'amount' => $amount ?? null,
                'transaction_amount' => $saleAmount ?? null,
                'created' => Carbon::parse($dataPaymentMethods['created'])->format('Y-m-d H:i:s') ?? null,
                'updated' => Carbon::parse($dataPaymentMethods['updated'])->format('Y-m-d H:i:s') ?? null,
                'description' => $dataPaymentMethods['description'] ?? null,
                'reference_id' => $dataPaymentMethods['reference_id'] ?? null,
                'failure_code' => $dataPaymentMethods['failure_code'] ?? null,
                'actions' => json_encode($dataPaymentMethods['actions'] ?? null) ?? null,
                'card' => json_encode($dataPaymentMethods['card'] ?? null) ?? null,
                'direct_debit' => json_encode($dataPaymentMethods['direct_debit'] ?? null) ?? null,
                'ewallet' => json_encode($dataPaymentMethods['ewallet'] ?? null) ?? null,
                'over_the_counter' => json_encode($dataPaymentMethods['over_the_counter'] ?? null) ?? null,
                'virtual_account' => json_encode($dataPaymentMethods['virtual_account'] ?? null) ?? null,
                'qr_code' => json_encode($dataPaymentMethods['qr_code'] ?? null) ?? null,
                'billing_information' => json_encode($dataPaymentMethods['billing_information'] ?? null) ?? null,
                'reusability' => $dataPaymentMethods['reusability'] ?? null,
                'direct_bank_transfer' => $dataPaymentMethods['direct_bank_transfer'] ?? null,
                'status' => $dataPaymentMethods['status'] ?? null,
                'metadata' => json_encode($dataPaymentMethods['metadata'] ?? null) ?? null,
            ];

            $xenditPaymentMethodData = xenditPaymentMethod::create($payloadPaymentMethod);


            $payloadBusinessAmount = [
                'business_id' => Auth::user()->business_id ?? null,
                'status_credit' => 1,
                'transactional_type' => xenditPaymentMethod::class ?? null,
                'transactional_id' => $xenditPaymentMethodData['id'] ?? null,
                'reference_id' => $dataPaymentMethods['reference_id'] ?? null,
                'amount' => $amount ?? null,
                'sale_amount' =>  $saleAmount ?? null,
                'received_amount' => 0,
                'fee_amount' => 0,
                'status' => 'PENDING_PAYMENT',
            ];

            businessAmount::create($payloadBusinessAmount);

            $result = $xenditPaymentRequest;
        } catch (\Xendit\XenditSdkException $e) {
            // $result = $e->getMessage();
            throw new \Exception(json_encode($e->getMessage()));
        }

        return response()->json([
            'message' => __('Success'),
            'data' => $result,
        ], 200);

     }

    public function showBalance($forUserId = null){
        $apiInstance = new PaymentMethodApi();
        Configuration::setXenditKey(env('XENDIT_KEY'));

        $customerId = '66fad06d0abd34c4121e089c'; // Ganti dengan customer_id Anda
        $apiKey = env('XENDIT_KEY');  // Ganti dengan API key Xendit Anda

        Configuration::setXenditKey($apiKey);

        $apiInstance = new BalanceApi();
        $account_type = "CASH";
        $currency = "IDR";
        $at_timestamp = Carbon::now()->toIso8601String();
        $for_user_id = $forUserId;

        try {
            $dataResponse = $apiInstance->getBalance($account_type, $currency, $at_timestamp, $for_user_id);
            $result = $dataResponse['balance'];
            // print_r($result);
        } catch (\Xendit\XenditSdkException $e) {
            $result = [];
        }
        return $result;

        // return view('layouts.app',compact('result','resultDetails'));

    }
    // public function create()
    // {

    //     return view('paymentgateway::create');
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request): RedirectResponse
    // {
    //     //
    // }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {


        Configuration::setXenditKey(env('XENDIT_KEY'));

        $apiInstance = new PaymentMethodApi();
        $for_user_id = "5f9a3fbd571a1c4068aa40cf"; // string
        $id = array('id_example'); // string[]
        $type = array('type_example'); // string[]
        $status = array(new \Xendit\PaymentMethod\PaymentMethodStatus()); // \PaymentMethod\PaymentMethodStatus[]
        $reusability = new \Xendit\PaymentMethod\PaymentMethodReusability(); // PaymentMethodReusability
        $customer_id = "'customer_id_example'"; // string
        $reference_id = "'reference_id_example'"; // string
        $after_id = "'after_id_example'"; // string
        $before_id = "'before_id_example'"; // string
        $limit = 56; // int

        try {
            $result = $apiInstance->getAllPaymentMethods($for_user_id, $id, $type, $status, $reusability, $customer_id, $reference_id, $after_id, $before_id, $limit);
            print_r($result);
        } catch (\Xendit\XenditSdkException $e) {
            echo 'Exception when calling PaymentMethodApi->getAllPaymentMethods: ', $e->getMessage(), PHP_EOL;
            echo 'Full Error: ', json_encode($e->getFullError()), PHP_EOL;
        }

        return view('paymentgateway::show', compact('result'));
        // return view('paymentgateway::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('paymentgateway::edit');
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, $id): RedirectResponse
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function createAccount(Business $business)
    {
        $base64 = base64_encode(config('setting.payments.xendit.development.secret_key').':');
        $secret_key = 'Basic ' . $base64;
        $accountId = config('setting.payments.xendit.development.parent_account_id');

        try {
            $dataRequest = Http::withHeaders([
                'Authorization' => $secret_key,
                'for-user-id' => $accountId
            ])->post('https://api.xendit.co/v2/accounts', [
                'email' => $business->branch->email,
                'type' => 'OWNED',
                'public_profile' => [
                    'business_name' => $business->name
                ]
            ]);

            $result = $dataRequest->object();
        } catch (\Exception $e) {
            return $e;
        }

        return $result;
    }
}
