<?php

namespace Modules\PaymentGateway\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\businessAmount;
use App\Models\DataConfig;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\PaymentGateway\Entities\XenditDisbursement;
use Modules\PaymentGateway\Entities\XenditDisbursementMethod;
use Modules\PaymentGateway\Entities\XenditPaymentMethod;
use Modules\PaymentGateway\Entities\XenditPaymentRequest;
use Modules\Whatsapp\Http\Controllers\WhatsappController;
use Xendit\Configuration;
use Xendit\Customer\CustomerRequest;
use Xendit\PaymentMethod\PaymentMethodApi;
use Illuminate\Support\Facades\Http;
use Modules\Income\Entities\Income;
use Modules\PaymentGateway\Entities\XenditCreatePayment;
use Modules\PaymentGateway\Entities\XenditPaylaterPlan;
use Modules\PaymentGateway\Entities\XenditPaylaterRequest;
use Modules\PaymentMethod\Entities\PaymentChannel;
use Modules\People\Entities\Customer;
use Modules\Product\Entities\Product;
use Modules\Sale\Entities\Sale;
use Xendit\BalanceAndTransaction\BalanceApi;
use Xendit\PaymentRequest\PaymentRequestApi;
use Xendit\PaymentRequest\PaymentRequestParameters;
use Xendit\Customer\CustomerApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;
use Illuminate\Support\Str;
use Modules\PaymentGateway\Entities\XenditCreateVirtualAccount;
use Modules\PaymentGateway\Entities\XenditDisbursementChannel;
use Modules\PaymentGateway\Entities\XenditInvoiceRequest;
use Modules\PaymentGateway\Entities\XenditVirtualAccountRequest;
use Modules\Sale\Entities\SelforderCheckout;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;
use Xendit\Invoice\Invoice;
use Xendit\Payout\CreatePayoutRequest;
use Xendit\Payout\PayoutApi;

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
    public function getDisbursementChannels(Request $request)
    {
        $xdmId = $request->get('xdm_id');
        $channels = XenditDisbursementChannel::where('xdm_id', $xdmId)
        ->orderBy('name')
        ->get(['id', 'name']);

        return response()->json($channels); // Kembalikan data dalam format JSON
    }
    public function createDisbursement(
        string $disMethod,
        string $disChannel,
        string $accountName,
        string $accountNo,
        float $amount,
        float $transactionAmount,
        string $notes)
    {
        Configuration::setXenditKey(config('services.xendit.key'));
        $idTransaction = str::orderedUuid()->toString();
        $reffPayment =  $idTransaction . '-' . Carbon::now()->format('Ymdss');

        $businessData = Business::find(Auth::user()->business_id);
        $disbursementMethodData = XenditDisbursementMethod::find($disMethod);
        $disbursementChannelData = XenditDisbursementChannel::find($disChannel);

        $apiInstance = new PayoutApi();
        $idempotencyKey = 'dist-'.rand(1,10000) . Carbon::now()->format('Ymmddss');
        $forUserId = null;
        $createPayoutRequestPayload = [
            'reference_id' => $reffPayment,
            'currency' => 'IDR',
            'channel_code' => $disbursementChannelData->code,
            'receipt_notification' => [
              'email_to' => [
                  $businessData->email
              ],
              'email_bcc' => json_decode( $disbursementMethodData->email_owner),
            ],
            'channel_properties' => [
              'account_holder_name' => $accountName,
              'account_number' => $accountNo,
            //   'account_type' => $disbursementMethodData->type
            ],
            'amount' => $transactionAmount,
            'description' => $notes,
            'type' => 'DIRECT_DISBURSEMENT'
          ];

        $createPayoutRequest = new CreatePayoutRequest($createPayoutRequestPayload);

        try {
            $apiResultCreate = $apiInstance->createPayout($idempotencyKey, $forUserId, $createPayoutRequest);
            // dd($apiResultCreate);
            $xenditDisbursment = XenditDisbursement::create([
                'id' => $idTransaction,
                'disbursement_id' =>  $apiResultCreate['id'],
                'reference_id' => $apiResultCreate['reference_id'],
                'channel_code' => $apiResultCreate['channel_code'],
                'channel_properties' => json_encode($apiResultCreate['channel_properties']),
                'amount' => $apiResultCreate['amount'],
                'description' => $apiResultCreate['description'],
                'currency' => $apiResultCreate['currency'],
                'receipt_notification' => json_encode($apiResultCreate['receipt_notification']),
                'metadata' => json_encode($apiResultCreate['metadata']),
                'created' => Carbon::parse($apiResultCreate['created'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                'updated' => Carbon::parse($apiResultCreate['updated'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                'xen_business_id' => $apiResultCreate['business_id'],
                'business_id' => $businessData->id,
                'status' => $apiResultCreate['status'],
                'failure_code' => $apiResultCreate['failure_code'],
                'estimated_arrival_time' => Carbon::parse($apiResultCreate['estimated_arrival_time'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
            ]);

            $payloadBusinessAmount = [
                'business_id' => Auth::user()->business_id ?? null,
                'status_credit' => -1,
                'transactional_type' => XenditDisbursement::class ?? null,
                'transactional_id' => $xenditDisbursment->id ?? null,
                'reference_id' => $apiResultCreate['reference_id'],
                'amount' => $transactionAmount ?? null,
                'transaction_amount' =>  $amount ?? null,
                'received_amount' => 0,
                'deduction_amount' => 0,
                'status' => 'PENDING',
            ];

            businessAmount::create($payloadBusinessAmount);

        } catch (\Xendit\XenditSdkException $e) {
            throw new \Exception(json_encode($e->getMessage()));
        }
        return $apiResultCreate;
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
    public function createCustomerHttp(
        string $reffId,
        string $firstName,
        string $lastName,
        string $dob,
        string $email,
        string $mobileNumber,
        string $gender,
        string $streetLine1,
        string $city,
        string $postalCode,
        string $description,
        ?string $businessId = null,

    )
    {
        $base64 = base64_encode(config('services.xendit.key').':');
        $secret_key = 'Basic ' . $base64;

        if (blank($businessId)){
            $businessId = Auth::user()->business_id;
        }
        try {

        $businessData = Business::find($businessId);

            $addressInfo = [
                [
                    'country' => 'ID',
                    'street_line1' => $streetLine1,
                    'city' => $city,
                    'postal_code' => $postalCode,
                    'category' => 'HOME',
                    'is_primary' => true
                ]
            ];

            $payloadRequest = [
                'client_name' => preg_replace('/[^a-zA-Z\s]/','', $businessData['name']),
                'reference_id' => $reffId,
                'type' => 'INDIVIDUAL',
                'individual_detail' => [
                    'given_names' => $firstName,
                    'surname' => $lastName,
                    'gender' => $gender,
                    'date_of_birth' => $dob,
                    'nationality' => 'ID',
                ],
                'description' => $description,
                'email' => $email,
                'mobile_number' => $mobileNumber,
                'addresses' => $addressInfo
            ];

            // DD($payloadRequest);
            $dataRequest = Http::withHeaders([
                'Authorization' => $secret_key,
                'for-user-id' => null
            ])->post('https://api.xendit.co/customers', $payloadRequest);

            $result = $dataRequest->object();

        } catch (\Exception $e) {
            return $e;
        }

        return $result;
    }
    public function updateCustomerHttp(
        string $id,
        string $refId,
        string $firstName,
        string $lastName,
        string $dob,
        string $email,
        string $mobileNumber,
        string $gender,
        string $streetLine1,
        string $city,
        string $postalCode,
        string $description,
        ){

            $base64 = base64_encode(config('services.xendit.key').':');
        $secret_key = 'Basic ' . $base64;

        try {

            $addressInfo = [
                [
                    'country' => 'ID',
                    'street_line1' => $streetLine1,
                    'city' => $city,
                    'postal_code' => $postalCode,
                    'category' => 'HOME',
                    'is_primary' => true
                ]
            ];

            $payloadRequest = [
                'reference_id' => $refId,
                'individual_detail' => [
                    'given_names' => $firstName,
                    'surname' => $lastName,
                    'gender' => $gender,
                    'date_of_birth' => $dob,
                    'nationality' => 'ID',
                ],
                'description' => $description,
                'email' => $email,
                'mobile_number' => $mobileNumber,
                'addresses' => $addressInfo
            ];

            // DD($payloadRequest);
            $dataRequest = Http::withHeaders([
                'Authorization' => $secret_key,
                'for-user-id' => null
            ])->patch('https://api.xendit.co/customers/'.$id, $payloadRequest);

            $result = $dataRequest->object();
        } catch (\Exception $e) {
            return $e;
        }

        return $result;
    }
    public function createTransactionInvoiceRequest(
        Customer $customer,
        array $orderedroducts,
        array $paymentMethods,
        array $fees,
        int $totalAmount,
        int $discountAmount,
        int $taxAmount,
        int $shippingAmount,
        int $saleAmount,
        ?int $expryDuration = 86400,
        ?bool $sendNotification = true,
        ?string $businessId = null,

    ){
        Configuration::setXenditKey(config('services.xendit.key'));
        $apiInstance = new InvoiceApi();
        $forUserId = null;
        $amount = 0;
        $additionalAmount = 0;
        $idTransaction = str::orderedUuid()->toString();
        $reffPayment =  $idTransaction . '-' . Carbon::now()->format('Ymdss');

        $createPaymentTransactionalType = XenditInvoiceRequest::class;
        if (blank($businessId)){
            $businessId = Auth::user()->business_id;
        };

        if (!blank($orderedroducts)){
            foreach($orderedroducts as $orderedroduct){

                $link = route('product.sale', ['product' => $orderedroduct['product_id']]);
                $productData = Product::find($orderedroduct['product_id']);
                $amount += $orderedroduct['sub_total'];
                $orderedItems[] = [
                    'name' => $orderedroduct['product_name'],
                    'quantity' => (int) $orderedroduct['quantity'],
                    'price' => $orderedroduct['unit_price'],
                    'category' => $productData->category->category_name,
                    'url' => $link,
                ];
            }
        }

        $notificationPreference = null;
        if ($sendNotification){
            $notificationPreference = [
                'invoice_created' => ['whatsapp', 'email'],
                'invoice_reminder' => ['whatsapp', 'email'],
                'invoice_paid' => ['whatsapp', 'email']
            ];
        }

        $additionalAmount += $fees['paymentFee'] + $fees['paymentFeePpn'] +  $fees['applicationFee'];

        $feesData = [];
        $feesData=[
                [
                    'type'  => 'Payment Fee',
                    'value' => $fees['paymentFee'],
                ],
                [
                    'type'  => 'Payment Fee PPN',
                    'value' => $fees['paymentFeePpn'] ?? 0,
                ],
                [
                    'type'  => 'Application Fee',
                    'value' => $fees['applicationFee'] ?? 0,
                ]
            ];
            if ($discountAmount > 0){
                $additionalAmount -= $discountAmount;
                $feesData[] =
                    [
                    'type'  => 'Discount',
                    'value' => ($discountAmount ?? 0) * -1
                    ];
            }
            if ($taxAmount > 0){
                $additionalAmount += $discountAmount;
                $feesData[] =
                    [
                    'type'  => 'Tax',
                    'value' => ($taxAmount ?? 0),
                    ];
            }
            if ($shippingAmount > 0){
                $additionalAmount += $discountAmount;
                $feesData[] = [
                    'type'  => 'Shipping',
                    'value' => ($shippingAmount ?? 0),
                    ];
            }
        $payloadCreateInvoice = [
            'external_id' => $reffPayment,
            'description' => 'Invoice for ' . $customer->customer_name . ' Date : ' . Carbon::now()->format('d-M-Y') . ' Total : ' . format_currency($totalAmount),
            'amount' => $totalAmount + $additionalAmount,
            'customer' => [
                'given_names'=> $customer->customer_first_name,
                'surname'=> $customer->customer_last_name,
                'email'=> $customer->customer_email,
                'mobile_number'=> $customer->customer_phone,
                'addresses'=> [
                    [
                        'city' => $customer->city,
                        'country' => 'ID',
                        'postal_code' => $customer->postal_code,
                        'state' => $customer->province,
                        'street_line1' => $customer->customer_address,
                    ]
                ],
            ],
            'customer_notification_preference' => $notificationPreference,
            'invoice_duration' => $expryDuration,
            'success_redirect_url' => route('succespayment'), //webView if Success
            'failure_redirect_url' => route('failedpayment'),

            'payment_methods' => $paymentMethods,
            'currency' => 'IDR',
            'mid_label' => 'MULIA',
            'reminder_time' => 1,
            'reminder_time_unit' => 'hours',
            'locale' => 'id',
            'items' => $orderedItems ?? null,
            'fees' => $feesData,
            'payer_email' => $customer->customer_email,
            'should_send_email' => $sendNotification,
            'should_authenticate_credit_card' => true,
            ];
        try {
            $createInvoiceRequest = new CreateInvoiceRequest($payloadCreateInvoice);
            $dataRequest = $apiInstance->createInvoice($createInvoiceRequest, $forUserId);
            $invoiceRequestPayload = [
                'id' => $idTransaction,
                'xen_invoice_id' => $dataRequest['id'],
                'customer_id' => $customer->id,
                'external_id' => $dataRequest['external_id'],
                'user_id' => $dataRequest['user_id'],
                'payer_email' => $dataRequest['payer_email'],
                'description' => $dataRequest['description'],
                'payment_method' => json_encode($paymentMethods),
                'status' => $dataRequest['status'],
                'merchant_name' => $dataRequest['merchant_name'],
                'merchant_profile_picture_url' => $dataRequest['merchant_profile_picture_url'],
                'locale' => $dataRequest['locale'] ?? 'id',
                'amount' => $dataRequest['amount'],
                'invoice_url' => $dataRequest['invoice_url'],
                'expiry_date' => Carbon::parse($dataRequest['expiry_date'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                'available_banks' => json_encode($dataRequest['available_banks']),
                'available_retail_outlets' => json_encode($dataRequest['available_retail_outlets']),
                'available_ewallets' => json_encode($dataRequest['available_ewallets']),
                'available_qr_codes' => json_encode($dataRequest['available_qr_codes']),
                'available_direct_debits' => json_encode($dataRequest['available_direct_debits']),
                'available_paylaters' => json_encode($dataRequest['available_paylaters']),
                'should_exclude_credit_card' => $dataRequest['should_exclude_credit_card'],
                'should_send_email' => $dataRequest['should_send_email'],
                'created' => Carbon::parse($dataRequest['created'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                'updated' => Carbon::parse($dataRequest['updated'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                'success_redirect_url' => $dataRequest['success_redirect_url'],
                'failure_redirect_url' => $dataRequest['failure_redirect_url'],
                'should_authenticate_credit_card' => $dataRequest['should_authenticate_credit_card'],
                'currency' => $dataRequest['currency'],
                'items' => json_encode($dataRequest['items']),
                'fixed_va' => $dataRequest['fixed_va'] ?? false,
                'reminder_date' => $dataRequest['reminder_date'],
                'customer' => json_encode($dataRequest['customer']),
                'customer_notification_preference' => json_encode($dataRequest['customer_notification_preference']),
                'fees' => json_encode($dataRequest['fees']),
                'channel_properties' => json_encode($dataRequest['channel_properties']),
            ];

            $xenditInvoiceRequest = XenditInvoiceRequest::create($invoiceRequestPayload);

            $xenditCreatePayments = XenditCreatePayment::create([
                'reference_id' => $reffPayment,
                'transactional_type' => $createPaymentTransactionalType,
                'transactional_id' => $xenditInvoiceRequest->id,
                'amount' => $xenditInvoiceRequest->amount,
                'transaction_amount' => $saleAmount ?? null,
                'payment_type' => 'INVOICE',
                'channel_code' => 'INVOICE_LINK',
                'status' => 'PENDING',
            ]);

            $payloadBusinessAmount = [
                'business_id' => $businessId,
                'status_credit' => 1,
                'transactional_type' => XenditInvoiceRequest::class ?? null,
                'transactional_id' => $xenditInvoiceRequest->id ?? null,
                'reference_id' => $reffPayment,
                'amount' => $xenditInvoiceRequest->amount ?? null,
                'transaction_amount' =>  $saleAmount ?? null,
                'received_amount' => 0,
                'deduction_amount' => 0,
                'status' => 'PENDING',
            ];


            businessAmount::create($payloadBusinessAmount);

            $result = [
                'id' => $xenditCreatePayments->id,
                'reference_id' => $xenditCreatePayments->reference_id,
                'invoice_requests' => $xenditInvoiceRequest,
            ];

        } catch (\Xendit\XenditSdkException $e) {
            throw new \Exception(json_encode($e->getMessage()));

        }

        return $result;

    }
    public function createInvoiceRequestSample(){

        Configuration::setXenditKey(config('services.xendit.key'));

        $apiInstance = new InvoiceApi();
        $payloadCreateInvoice = [
            'external_id' =>  str::orderedUuid()->toString(),
            'description' => 'Test Invoice',
            'amount' => 10000,
            'customer' => [
                'given_names'=> 'Munggi',
                'surname'=> 'Priramdona',
                'email'=> 'munggi.priramdona@gmail.com',
                'mobile_number'=> '+6281314569044',
                'addresses'=> [
                    [
                    'city' => 'Jakarta Selatan',
                    'country' => 'Indonesia',
                    'postal_code' => '12345',
                    'state' => 'Daerah Khusus Ibukota Jakarta',
                    'street_line1' => 'Jalan Makan',
                    ]
                ],
            ],
            'customer_notification_preference' => [
                'invoice_created' => ['whatsapp', 'email'],
                'invoice_reminder' => ['whatsapp', 'email'],
                'invoice_paid' => ['whatsapp', 'email']
            ],
            'invoice_duration' => 86400,
            'success_redirect_url' => route('succespayment'), //webView if Success
            'failure_redirect_url' => route('failedpayment'),

            'payment_methods' => [
                "CREDIT_CARD",
                // "BCA", "BNI", "BSI", "BRI", "MANDIRI", "PERMATA", "SAHABAT_SAMPOERNA",
                // "BNC", "ALFAMART", "INDOMARET",
                // "OVO", "DANA", "SHOPEEPAY", "LINKAJA", "JENIUSPAY", "DD_BRI", "DD_BCA_KLIKPAY", "KREDIVO", "AKULAKU", "UANGME", "ATOME", "QRIS"],
            ],
            'currency' => 'IDR',
            'mid_label' => 'MULIA',
            // 'callback_virtual_account_id' => '',//will use when VA payment_method included

            'reminder_time' => 1,
            'reminder_time_unit' => 'hours',
            'locale' => 'id',
            'items' => [
                [
                    'name' => 'Paramex',
                    'quantity' => 11,
                    'price' => 137861,
                    'category' => 'Obat',
                    'url' => 'http://127.0.0.1:8881/product-sale/9d5a12e0-597f-4748-935a-5ac17c709dfa'
                ]
            ],
            'fees' => [
                [
                 'type'  => 'ADMIN',
                 'value' => 5000,
                ]
            ],
            'payer_email' => 'munggi.priramdona@gmail.com',
            'should_send_email' => true,
            'should_authenticate_credit_card' => true,
            // 'channel_properties' => [
            //     'cards' => [
            //         'allowed_bins' => ['400000','52000000'],

            //         'installment_configuration' => [
            //             'allow_installment' => true,
            //             'allow_full_payment' => true,
            //             'allowed_terms' => [
            //                 [
            //                     'issuer' => 'BCA',
            //                     'terms' => [ 3,6,12]
            //                 ],[
            //                     'issuer' => 'BNI',
            //                     'terms' => [ 3,6,12]
            //                 ],[
            //                     'issuer' => 'BRI',
            //                     'terms' => [ 3,6,12]
            //                 ],[
            //                     'issuer' => 'PERMATA',
            //                     'terms' => [ 3,6,12]
            //                 ],

            //                 ]
            //             ]
            //         ]
            //     ],

            ];

        $createInvoiceRequest = new CreateInvoiceRequest($payloadCreateInvoice);

        $forUserId = null;

        try {
            $result = $apiInstance->createInvoice($createInvoiceRequest, $forUserId);
            dd($result);

        } catch (\Xendit\XenditSdkException $e) {
            throw new \Exception(json_encode($e->getMessage()));

        }
    }
    public function createPaylaterRequest(
        string $planId,
        string $refId,
        string $customerId,
        string $xenditPaylaterPlanId,
        int $saleAmount,
        ?string $businessId = null,
        ){

            $createPaymentTransactionalType = XenditPaylaterRequest::class;
            $createPaymentTransactionalId = null;
            $base64 = base64_encode(config('services.xendit.key').':');
            $secret_key = 'Basic ' . $base64;
            $url = 'https://api.xendit.co/paylater/charges';

            if (blank($businessId)){
                $businessId = Auth::user()->business_id;
            }

        try {

            $payloadRequest = [
                "plan_id" => $planId,
                "reference_id" => $refId,
                "checkout_method" => "ONE_TIME_PAYMENT",
                'success_redirect_url' => route('succespayment'), //webView if Success
                'failure_redirect_url' => route('failedpayment'), //webView if Failed
            ];

            $dataRequest = Http::withHeaders([
                'Authorization' => $secret_key,
                'for-user-id' => null
            ])->post($url, $payloadRequest);

            $apiResult = $dataRequest->object();
            $xenditPaylaterRequestPayload =
            [
                'xen_business_id' => $apiResult->business_id,
                'reference_id' => $apiResult->reference_id,
                'customer_id' => $customerId,
                'cust_id' => $apiResult->customer_id,
                'xendit_paylater_plan_id' => $xenditPaylaterPlanId,
                'plan_id' => $apiResult->plan_id,
                'currency' => $apiResult->currency,
                'amount' => $apiResult->amount,
                'channel_code' => $apiResult->channel_code,
                'checkout_method' => $apiResult->checkout_method,
                'status' => $apiResult->status,
                'actions' => json_encode($apiResult->actions), //array
                'expires_at' => Carbon::parse($apiResult->expires_at)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                'success_redirect_url' => $apiResult->success_redirect_url,
                'failure_redirect_url' => $apiResult->failure_redirect_url,
                'callback_url' => $apiResult->callback_url,
                'created' => Carbon::parse($apiResult->created)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                'updated' => Carbon::parse($apiResult->updated)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                'order_items' => json_encode($apiResult->order_items),
            ];

            $xenditPaylaterRequest = XenditPaylaterRequest::create($xenditPaylaterRequestPayload);


            $payloadBusinessAmount = [
                'business_id' => $businessId,
                'status_credit' => 1,
                'transactional_type' => XenditPaylaterRequest::class ?? null,
                'transactional_id' => $xenditPaylaterRequest->id ?? null,
                'reference_id' => $apiResult->reference_id,
                'amount' => $apiResult->amount ?? null,
                'transaction_amount' =>  $saleAmount ?? null,
                'received_amount' => 0,
                'deduction_amount' => 0,
                'status' => 'PENDING',
            ];

            businessAmount::create($payloadBusinessAmount);

            $xenditCreatePayments = XenditCreatePayment::create([
                'reference_id' => $refId,
                'transactional_type' => $createPaymentTransactionalType,
                'transactional_id' => $xenditPaylaterRequest->id,
                'amount' => $apiResult->amount,
                'transaction_amount' => $saleAmount ?? null,
                'payment_type' => 'PAYLATER',
                'channel_code' => $apiResult->channel_code,
                'status' => 'PENDING',
            ]);

            $result = [
                'id' => $xenditCreatePayments->id,
                'reference_id' => $xenditPaylaterRequest->reference_id,
                'paylater_requests' => $xenditPaylaterRequest,
            ];

        } catch (\Exception $e) {
            return $e;
        }

        return $result;
    }
    public function paylaterPlans(
        string $customerId,
        array $paymentFees,
        int $taxAmount,
        int $discountAmount,
        int $shippingAmount,
        array $products,
        mixed $paymentChannel,
        int $totalAmount,
        int $saleAmount,
        ){
            $customerData = Customer::find($customerId);
            $additionalAmount = 0;
            $idTransaction = str::orderedUuid()->toString();
            $base64 = base64_encode(config('services.xendit.key').':');
            $secret_key = 'Basic ' . $base64;
            $url = 'https://api.xendit.co/paylater/plans';
            $orderedItems = [];

        try {

            foreach($products as $product){

                $link = route('product.sale', ['product' => $product['product_id']]);
                $productData = Product::find($product['product_id']);

                $orderedItems[] = [
                    "type" => "PHYSICAL_PRODUCT",
                    "reference_id" => $productData->id,
                    "name" => $product['product_name'],
                    "net_unit_amount" => $product['sub_total'],
                    "quantity" => (int) $product['quantity'],
                    "url" => $link,
                    "category" => $productData->category->category_name,
                    "description" => $productData->product_note,
                ];

            }

            // foreach($paymentFees as $paymentFee){
            $totalFee = format_currency(round($paymentFees['totalFee']));
            $paymentFee = format_currency(round($paymentFees['paymentFee']));
            $paymentFeePpn = format_currency(round($paymentFees['paymentFeePpn']));
            $applicationFee = format_currency(round($paymentFees['applicationFee']));

            $paymentFee1 = "free";
            $paymentFee2 = "free";
            $paymentFeeAmount1 = 0;
            $paymentFeeAmount2 = 0;

            if(!blank($paymentChannel['fee_type_1'])){
                if ($paymentChannel['fee_type_1'] == '%'){
                    $paymentFee1 = round($paymentChannel['fee_value_1'],2).'% From Amount';
                    $paymentFeeAmount1 = format_currency(round(($saleAmount * $paymentChannel['fee_value_1']) / 100));
                }else{
                    $paymentFee1 = str_replace('Rp. ',"", format_currency($paymentChannel['fee_value_1']));
                    $paymentFeeAmount1 = format_currency($paymentChannel['fee_value_1']);
                }
            }

            if(!blank($paymentChannel['fee_type_2'])){
                if ($paymentChannel['fee_type_2'] == '%'){
                    $paymentFee2 = round($paymentChannel['fee_value_2'],2).'%';
                    $paymentFeeAmount2 = format_currency(round(($saleAmount * $paymentChannel['fee_value_2']) / 100));
                }else{
                    $paymentFee2 = str_replace('Rp. ',"", format_currency($paymentChannel['fee_value_2']));
                    $paymentFeeAmount2 = format_currency($paymentChannel['fee_value_2']);
                }
            }
                $additionalAmount += round($paymentFees['totalFee']);
                $dataConfig = DataConfig::first();
                $linkPayment = route('channel.product', ['channel' => $paymentChannel['id']]);
                // $productData = Product::find($product['product_id']);
                $isPPnFee = $paymentChannel['is_ppn']==1 ? "True" : "False" ;
                $orderedItems[] = [
                    "type" => "FEE",
                    "reference_id" => $paymentChannel['id'],
                    "name" => $paymentChannel['name'],
                    "net_unit_amount" => round($paymentFees['totalFee']),
                    "quantity" => 1,
                    "url" => $linkPayment,
                    "category" => $paymentChannel['type'],
                    "description" => "TotalFee : " . $totalFee  .
                    " Froms (Fee1 [" . $paymentFee1 ."] is " . $paymentFeeAmount1 .
                    ") + (Fee2 [" . $paymentFee2 ."] is " . $paymentFeeAmount2 . ") " .
                    "+ (PPNFee ". $isPPnFee . " [" . round($dataConfig->ppn_value,2) . '% From (Fee1+Fee2)] ' .
                    ' is '.$paymentFeePpn .")"
                ];

                if ($discountAmount > 0 ){
                    $additionalAmount += round($discountAmount * -1);
                    $orderedItems[] = [
                        "type" => "DISCOUNT",
                        "reference_id" => $idTransaction,
                        "name" => 'Discount Sub Total',
                        "net_unit_amount" => round($discountAmount * -1),
                        "quantity" => 1,
                        "url" => $linkPayment,
                        "category" => 'DISCOUNT',
                        "description" => "Global Discount from Sub Total"
                    ];
                }
            // }
            $payloadRequest = [
                "customer_id" => $customerData->cust_id,
                "channel_code" => $paymentChannel['code'],
                "currency" => "IDR",
                "amount" => $totalAmount + $additionalAmount,
                "order_items" => $orderedItems
            ];

            $dataRequest = Http::withHeaders([
                'Authorization' => $secret_key,
                'for-user-id' => null
            ])->post($url, $payloadRequest);

            if ($dataRequest->failed()) {
                throw new Exception('Error Request Plan');
            }

            $apiResult = $dataRequest->object();

            $result = XenditPaylaterPlan::create([
                'id' => $idTransaction,
                'plan_id' => $apiResult->id,
                'customer_id' => $customerId,
                'cust_id' => $apiResult->customer_id,
                'channel_code' => $apiResult->channel_code,
                'currency' => $apiResult->currency,
                'amount' => $apiResult->amount,
                'created' => Carbon::parse($apiResult->created)->format('Y-m-d H:i:s'),
                'order_items' => json_encode($apiResult->order_items),
                'options' => json_encode($apiResult->options),
            ]);
        } catch (Exception $e) {
            // Tangani exception atau berikan pesan error
            throw new \Exception("Request failed with status: " . $e->getMessage());
        }

        return $result;
    }

    public function updateCustomer(
        string $id,
        string $refId,
        string $firstName,
        string $lastName,
        string $dob,
        string $email,
        string $mobileNumber,
        string $gender,
        string $streetLine1,
        string $city,
        string $province,
        string $postalCode,
        string $description,
        ){
        Configuration::setXenditKey(config('services.xendit.key'));
        $apiInstance = new CustomerApi();
        $idempotency_key = 'cust' . rand(1,10000) . Carbon::now()->format('Ymmddss');
        $forUserId = null;

        try {

        $addressInfo = [
            [
                'country' => 'ID',
                'street_line1' => $streetLine1,
                'city' => $city,
                'postal_code' => $postalCode,
                'category' => 'HOME',
                'is_primary' => true
            ]
        ];

        $payloadRequest = [
            'reference_id' => $refId,
            'individual_detail' => [
                'given_names' => $firstName,
                'surname' => $lastName,
                'gender' => $gender,
                'date_of_birth' => $dob,
                'nationality' => 'ID',
            ],
            'description' => $description,
            'email' => $email,
            'mobile_number' => $mobileNumber,
            'addresses' => $addressInfo
        ];

        $result = $apiInstance->updateCustomer($id, $forUserId, $payloadRequest);

        return $result;

        } catch (\Xendit\XenditSdkException $e) {
            throw new \Exception(json_encode($e->getMessage()));
        }
    }
    public function createCustomer(
        string $reffId,
        string $firstName,
        string $lastName,
        string $dob,
        string $email,
        string $mobileNumber,
        string $gender,
        string $streetLine1,
        string $city,
        string $province,
        string $postalCode,
        string $description,
        ?string $businessId = null
        ){
        Configuration::setXenditKey(config('services.xendit.key'));
        $apiInstance = new CustomerApi();
        $idempotency_key = 'cust' . rand(1,10000) . Carbon::now()->format('Ymmddss');
        $forUserId = null;

        try {

            if (blank($businessId)){
                $businessData = Business::find(Auth::user()->business_id);
            }else{
                $businessData = Business::find($businessId);
            }
        $addressInfo = [
            [
                'country' => 'ID',
                'street_line1' => $streetLine1,
                'city' => $city,
                'postal_code' => $postalCode,
                'category' => 'HOME',
                'is_primary' => true
            ]
        ];

        $payloadRequest = [
            'client_name' => preg_replace('/[^a-zA-Z\s]/','', $businessData['name']),
            'reference_id' => $reffId,
            'type' => 'INDIVIDUAL',
            'individual_detail' => [
                'given_names' => $firstName,
                'surname' => $lastName,
                'gender' => $gender,
                'date_of_birth' => $dob,
                'nationality' => 'ID',
            ],
            'description' => $description,
            'email' => $email,
            'mobile_number' => $mobileNumber,
            'addresses' => $addressInfo
        ];

        $customer_request = new CustomerRequest($payloadRequest);

        $result = $apiInstance->createCustomer($idempotency_key, $forUserId, $customer_request);
        // dd($result);
        return $result;

        } catch (\Xendit\XenditSdkException $e) {
            throw new \Exception(json_encode($e->getMessage()));
        }
    }

    public function createQRCode(
        string $refId,
        string $channelCode,
        float $totalAmount,
        float $saleAmount,
        ){

            $base64 = base64_encode(config('services.xendit.key').':');
            $secret_key = 'Basic ' . $base64;
            $url = 'https://api.xendit.co/qr_codes';

        try {
            $timestamp = Carbon::now(config('app.timezone'))->addDay()->toIso8601String();

            $payloadRequest = [
                "reference_id" => $refId,
                "type" => 'DYNAMIC',
                "currency" => 'IDR',
                "amount" => $totalAmount,
                "expires_at" => $timestamp,
            ];

            $dataRequest = Http::withHeaders([
                'Authorization' => $secret_key,
                'for-user-id' => null
            ])->post($url, $payloadRequest);


            dd($dataRequest);
            if ($dataRequest->failed()) {
                throw new Exception('Error Request Create QR Code');
            }

            $apiResult = $dataRequest->object();

            // $payloadBusinessAmount = [
            //     'business_id' => Auth::user()->business_id ?? null,
            //     'status_credit' => 1,
            //     'transactional_type' => XenditVirtualAccountRequest::class ?? null,
            //     'transactional_id' => $xenditCreateVirtualAccount->id ?? null,
            //     'reference_id' => $apiResult->external_id,
            //     'amount' => $totalAmount ?? null,
            //     'transaction_amount' =>  $saleAmount ?? null,
            //     'received_amount' => 0,
            //     'deduction_amount' => 0,
            //     'status' => 'PENDING_PAYMENT',
            // ];

            // businessAmount::create($payloadBusinessAmount);

            // $xenditCreatePayments = XenditCreatePayment::create([
            //     'reference_id' => $apiResult->external_id,
            //     'transactional_type' => XenditVirtualAccountRequest::class,
            //     'transactional_id' => $xenditCreateVirtualAccount->id,
            //     'amount' => $totalAmount ?? null,
            //     'transaction_amount' => $saleAmount ?? null,
            //     'payment_type' => 'VIRTUAL_ACCOUNT',
            //     'channel_code' => $channelCode,
            //     'status' => 'PENDING',
            // ]);


            // $result = [
            //     'id' => $xenditCreatePayments->id,
            //     'reference_id' => $xenditCreatePayments->reference_id,
            //     // 'virtual_account' => $xenditCreateVirtualAccount,
            // ];

        } catch (Exception $e) {
            // Tangani exception atau berikan pesan error
            throw new \Exception("Request failed with status: " . $e->getMessage());
        }

        return $result;
    }

    public function createVirtualAccount(
        string $refId,
        string $channelCode,
        float $totalAmount,
        float $transactionAmount,
        ?string $businessId = null
        ){

            $base64 = base64_encode(config('services.xendit.key').':');
            $secret_key = 'Basic ' . $base64;
            $url = 'https://api.xendit.co/callback_virtual_accounts';

        // try {
            if(blank($businessId)){
                $businessId = Auth::user()->business_id;
            }

            $timestamp = Carbon::now(config('app.timezone'))->addDay()->toIso8601String();

            $getBusinessData = Business::find($businessId);

            $payloadRequest = [
                "external_id" => $refId,
                "bank_code" => $channelCode,
                "name" => preg_replace('/[^a-zA-Z\s]/','', $getBusinessData->name),
                "is_closed" => true,
                "is_single_use" => true,
                "expected_amount" => $totalAmount,
                "expiration_date" => $timestamp
            ];

            $dataRequest = Http::withHeaders([
                'Authorization' => $secret_key,
                'for-user-id' => null
            ])->post($url, $payloadRequest);

            if ($dataRequest->failed()) {
                throw new Exception('Error Request Create Virtual Account');
            }

            $apiResult = $dataRequest->object();

            $xenditCreateVirtualAccount = XenditVirtualAccountRequest::create([
                'xen_virtual_account_id' => $apiResult->id,
                'external_id' => $apiResult->external_id,
                'owner_id' => $apiResult->owner_id,
                'bank_code' => $apiResult->bank_code,
                'merchant_code' => $apiResult->merchant_code,
                'account_number' => $apiResult->account_number,
                'name' => $apiResult->name,
                'expected_amount' => 0,
                'is_single_use' => $apiResult->is_single_use,
                'is_closed' => $apiResult->is_closed,
                'expiration_date' => Carbon::parse($apiResult->expiration_date)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                'status' => $apiResult->status,
                'currency' => $apiResult->currency,
                'country' => $apiResult->country,
                'created' => null,
                'updated' => null,
            ]);
            $payloadBusinessAmount = [
                'business_id' => $businessId,
                'status_credit' => 1,
                'transactional_type' => XenditVirtualAccountRequest::class ?? null,
                'transactional_id' => $xenditCreateVirtualAccount->id ?? null,
                'reference_id' => $apiResult->external_id,
                'amount' => $totalAmount ?? null,
                'transaction_amount' =>  $transactionAmount ?? null,
                'received_amount' => 0,
                'deduction_amount' => 0,
                'status' => 'PENDING',
            ];

            businessAmount::create($payloadBusinessAmount);

            $xenditCreatePayments = XenditCreatePayment::create([
                'reference_id' => $apiResult->external_id,
                'transactional_type' => XenditVirtualAccountRequest::class,
                'transactional_id' => $xenditCreateVirtualAccount->id,
                'amount' => $totalAmount ?? null,
                'transaction_amount' => $transactionAmount ?? null,
                'payment_type' => 'VIRTUAL_ACCOUNT',
                'channel_code' => $channelCode,
                'status' => 'PENDING',
            ]);


            $result = [
                'id' => $xenditCreatePayments->id,
                'reference_id' => $xenditCreatePayments->reference_id,
                'virtual_account' => $xenditCreateVirtualAccount,
            ];

        // } catch (Exception $e) {
        //     // Tangani exception atau berikan pesan error
        //     throw new \Exception("Request failed with status: " . $e->getMessage());
        // }

        return $result;
    }
    public function createPaymentRequest(string $refId,
                                        ?string $forUserId = null,
                                        ?string $withSplitRule = null,
                                        int $amount,
                                        int $saleAmount,
                                        string $type,
                                        string $channelCode,
                                        mixed $transactionalType,
                                        ?string $businessId = null,
                                        ?string $reusability = 'ONE_TIME_USE',
                                        ?string $phoneNumber = null,
                                        ?array $basket = null,
                                        ?array $metadata = null,
                                        ?string $transactionalId = null){

        Configuration::setXenditKey(config('services.xendit.key'));
        $apiInstance = new PaymentRequestApi();
        $idempotency_key = rand(1,10000) . Carbon::now()->format('Ymmddss');
        $paymentMethod = null;
        $channelProperties = null;
        if(blank($businessId)){
            $businessId = Auth::user()->business_id;
        }

        $getBusinessData = Business::find($businessId);
        $payloadType = null;

        $createPaymentTransactionalType = XenditPaymentRequest::class;
        $createPaymentTransactionalId = null;

        if ($transactionalType == 'sale'){
            $transactionModel = Sale::class;
        }elseif($transactionalType == 'income')
        {
            $transactionModel = Income::class;
        }else{
            $transactionModel = SelforderCheckout::class;
        }

        if ($type === 'EWALLET'){
            if ($channelCode == 'OVO'){
                $channelProperties = [
                    'mobile_number' => '62'.$phoneNumber,
                    'expires_at' => Carbon::now()->addMinutes(60)->toIso8601String(),
                ];
            }
            else if ($channelCode == 'DANA' || $channelCode == 'LINKAJA' || $channelCode == 'SHOPEEPAY'  ){
                $channelProperties = [
                    'success_return_url' => route('succespayment'),
                ];
            }
            else{
                $channelProperties = [
                    'success_return_url' => route('succespayment'),
                    'failure_return_url' =>route('failed'),
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
            // dd($dataResult);
            $resultDetails = null;
            $dataPaymentMethods = json_decode($dataResult['payment_method'],true);
            $referenceId = $dataPaymentMethods['reference_id'] ?? null;
            $xenditPaymentRequestResponsePayload = [
                'payment_request_id'=> $dataResult['id'],
                'created'=> Carbon::parse($dataResult['created'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),  // Konversi ke format MySQL
                'updated'=> Carbon::parse($dataResult['updated'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                'reference_id'=> $referenceId,
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

            $xenditPaymentRequest = XenditPaymentRequest::create($xenditPaymentRequestResponsePayload);
            $createPaymentTransactionalId= $xenditPaymentRequest->id;

            $payloadPaymentMethod =[
                'pm_id' => $dataPaymentMethods['id'] ?? null,
                'business_id' => $dataPaymentMethods['business_id'] ?? null,
                'customer_id' => $dataPaymentMethods['customer_id'] ?? null,
                'xendit_payment_request_id' => $xenditPaymentRequest['id'] ?? null,
                'type' => $dataPaymentMethods['type'] ?? null,
                'country' => $dataPaymentMethods['country'] ?? null,
                'amount' => $amount ?? null,
                'transaction_amount' => $saleAmount ?? null,
                'transactional_type' => $transactionModel,
                'created' => Carbon::parse($dataPaymentMethods['created'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s') ?? null,
                'updated' => Carbon::parse($dataPaymentMethods['updated'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s') ?? null,
                'description' => $dataPaymentMethods['description'] ?? null,
                'reference_id' => $referenceId,
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

            $xenditPaymentMethodData = XenditPaymentMethod::create($payloadPaymentMethod);
            $payloadBusinessAmount = [
                'business_id' => $businessId,
                'status_credit' => 1,
                'transactional_type' => XenditPaymentMethod::class ?? null,
                'transactional_id' => $xenditPaymentMethodData['id'] ?? null,
                'reference_id' => $referenceId,
                'amount' => $amount ?? null,
                'transaction_amount' =>  $saleAmount ?? null,
                'received_amount' => 0,
                'deduction_amount' => 0,
                'status' => 'PENDING',
            ];

            businessAmount::create($payloadBusinessAmount);

           $xenditCreatePayments = XenditCreatePayment::create([
                'reference_id' => $referenceId,
                'transactional_type' => $createPaymentTransactionalType,
                'transactional_id' => $createPaymentTransactionalId,
                'amount' => $amount ?? null,
                'transaction_amount' => $saleAmount ?? null,
                'payment_type' => $type,
                'channel_code' => $channelCode,
                'status' => 'PENDING',
            ]);

            $waController = New WhatsappController();

            // $cekStatusDevice = $waController->waCekDevice();

            // if ($cekStatusDevice){
            //     $waController->sendMessageWa('Submit Request : ' .
            //         'Amount : '. format_currency($amount) .
            //         ' Sale : '. format_currency($saleAmount) .
            //         ' Fee : '. format_currency($amount - $saleAmount) .
            //         ' type : ' . $type . ' Code : ' . $channelCode);
            // }


            $result = [
                'id' => $xenditCreatePayments->id,
                'reference_id' => $xenditCreatePayments->reference_id,
                'payment_requests' => $xenditPaymentRequest,
            ];

            // $result = $xenditPaymentRequest;

        } catch (\Xendit\XenditSdkException $e) {
            throw new \Exception(json_encode($e->getMessage()));
        }

        return response()->json([
            'message' => __('Success'),
            'data' => $result,
        ], 200);

     }
     public function showBalance($forUserId = null){

        $businessId = Auth::user()->business_id;

        $balanceTransaction = BusinessAmount::where('business_id', $businessId)
        ->where('status','Completed')
        // ->where('transactional_type', 'Modules\PaymentGateway\Entities\XenditDisbursement')
        ->sum('calculated_transaction_amount');

        return $balanceTransaction;

    }

    public function showBalanceBackup($forUserId = null){
        $apiInstance = new PaymentMethodApi();
        Configuration::setXenditKey(config('services.xendit.key'));

        $customerId = '66fad06d0abd34c4121e089c';
        $apiKey = config('services.xendit.key');

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

    }
     public function payCC()
     {
         return view('paymentgateway::payment.cc');
     }
    public function show($id)
    {


        Configuration::setXenditKey(config('services.xendit.key'));

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
