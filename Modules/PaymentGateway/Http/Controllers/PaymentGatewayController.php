<?php

namespace Modules\PaymentGateway\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Xendit\Configuration;
use Xendit\PaymentMethod\PaymentMethodApi;
use Illuminate\Support\Facades\Http;
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

        Configuration::setXenditKey(env('XENDIT_KEY'));

        $customerId = '66fad06d0abd34c4121e089c'; // Ganti dengan customer_id Anda

        $apiInstance = new PaymentRequestApi();
        $for_user_id = null; // string// string[]
        $reference_id = []; // st
        $id =[]; // string[]
        // $idempotency_key = rand(1,10000);
        $customer_id = []; // string[]
        $limit = 56; // int
        $before_id = null; // string
        $after_id =null; // string


        try {
            $result = $apiInstance->getAllPaymentRequests($for_user_id, $reference_id, $id, $customer_id, $limit, $before_id, $after_id);

            $resultDetails = null;
        } catch (\Xendit\XenditSdkException $e) {

            $result = $e->getMessage();
            $resultDetails = json_encode($e->getFullError());
        }

        return view('paymentgateway::index',compact('result','resultDetails'));
    }

    public function createPaymentRequest($refId,
                                        $forUserId = null,
                                        $withSplitRule = null,
                                        int $amount,
                                        string $type,
                                        string $channelCode,
                                        string $successReturn,
                                        string $reusability){

        Configuration::setXenditKey(env('XENDIT_KEY'));
        $apiInstance = new PaymentRequestApi();
        $idempotency_key = rand(1,10000);
        $for_user_id = null; // string
        $with_split_rule = null; // string
        $paymentMethod = [];

        if ($type = 'EWALLET' && $channelCode = 'SHOPEEPAY'){
            $paymentMethod = [
                'type' => 'EWALLET',
                'ewallet' => [
                  'channel_code' => 'SHOPEEPAY',
                  'channel_properties' => [
                    'success_return_url' => 'https://redirect.me/success'
                  ]
                ],
                'reusability' => 'ONE_TIME_USE'
            ];
        }
        if ($type = 'QR_CODE'){
            $paymentMethod =  [
                'type' => 'QR_CODE',
                'reusability' => 'ONE_TIME_USE',
                'qr_code' => [
                  'channel_code' => 'QRIS'
                ]
              ];
        }

        $paymentRequestParameters = new PaymentRequestParameters([
          'reference_id' => 'example-ref-1234',
          'amount' => $amount,
          'currency' => 'IDR',
          'country' => 'ID',
          'metadata' => [
              'sku' => 'example-sku-1234'
          ],
          'payment_method' => $paymentMethod
        ]);

        try {
            $result = $apiInstance->createPaymentRequest($idempotency_key, $forUserId, $withSplitRule, $paymentRequestParameters);

            $resultDetails = null;
        } catch (\Xendit\XenditSdkException $e) {

            $result = $e->getMessage();
            $resultDetails = json_encode($e->getFullError());
        }

        return view('paymentgateway::index',compact('result','resultDetails'));
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
    public function create()
    {

        return view('paymentgateway::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
