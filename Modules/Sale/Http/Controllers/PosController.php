<?php

namespace Modules\Sale\Http\Controllers;

use App\Models\DataConfig;
use App\Models\masterConfig;
use App\Models\MasterConfig as ModelsMasterConfig;
use Carbon\Carbon;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\PaymentGateway\Entities\XenditPaymentMethod;
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
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use Modules\PaymentGateway\Entities\XenditCreatePayment;
use Modules\PaymentGateway\Entities\XenditPaymentRequest;
use Modules\PaymentGateway\Http\Controllers\PaymentGatewayController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Milon\Barcode\Facades\DNS1DFacade;
use Milon\Barcode\Facades\DNS2DFacade;
use Modules\PaymentGateway\Entities\XenditPaylaterPlan;
// use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PosController extends Controller
{
    public function printPos($id)
    {
        $sale = Sale::find($id);
        $url = route('sales.showdata', ['sale' => $sale]);
        $barcodeUrl = DNS2DFacade::getBarcodePNG($url, 'QRCODE',5,5);
        // return view('sale::print-pos-old', ['sale' => $sale, 'barcode' => $barcodeUrl]);

        // $viewContent = view('sale::print-pos', ['sale' => $sale, 'barcode' => $barcodeUrl])->render();
        $lineHeight = 41;
        $numberOfItems = count($sale->saleDetails);
        $estimatedHeight = ($numberOfItems * $lineHeight) + 400;

        $heightMM =  (($estimatedHeight / 96) * 30) *3;
        $pdf = PDF::loadView('sale::print-pos', ['sale' => $sale, 'barcode' => $barcodeUrl , 'publicUrl' => ''])
        ->setPaper([0, 0, 226, $heightMM], 'portrait');

        // Render PDF untuk mendapatkan output
        $output = $pdf->download();

        $filePath = storage_path('app/public/invoices/invoice_' . $sale->id . '.pdf');

        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }


        file_put_contents($filePath, $output);
        $publicUrl = asset('storage/invoices/invoice_' . $sale->id . '.pdf'); // URL yang dapat diakses oleh Android

        // dd($publicUrl);
        // return response()->stream(
        //     function () use ($output) {
        //         echo $output;
        //     },
        //     200,
        //     [
        //         'Content-Type' => 'application/pdf',
        //         'Content-Disposition' => 'inline; filename="invoice.pdf"',
        //     ]
        // );

        return view('sale::print-pos', ['sale' => $sale, 'barcode' => $barcodeUrl, 'publicUrl' => $publicUrl]);

        // return response()->json([
        //     'pdf_url' => $filePath,
        //     'message' => "<script>window.location.href = '$publicUrl'; setTimeout(() => { Android.printPage(); }, 1000);</script>"
        // ]);
    }
    public function printPosOri($id)
    {
        $sale = Sale::find($id);
        $url = route('sales.showdata', ['sale' => $sale]);

        $barcodeUrl = DNS2DFacade::getBarcodePNG($url, 'QRCODE',5,5);


        $pdf = PDF::loadView('sale::print-pos', [
            'sale' => $sale,
            'barcode' => $barcodeUrl
        ])->setPaper('a7');

        return $pdf->stream('sale-'. $sale->reference .'.pdf');
    }
    public function printPosBackup($id)
    {
        $sale = Sale::find($id);
        $url = route('sales.showdata', ['sale' => $sale]);
        $barcodeUrl = DNS2DFacade::getBarcodePNG($url, 'QRCODE',5,5);
        // return view('sale::print-pos-old', ['sale' => $sale, 'barcode' => $barcodeUrl]);

        // $viewContent = view('sale::print-pos', ['sale' => $sale, 'barcode' => $barcodeUrl])->render();
        $lineHeight = 41;
        $numberOfItems = count($sale->saleDetails);
        $estimatedHeight = ($numberOfItems * $lineHeight) + 400;

        $heightMM =  (($estimatedHeight / 96) * 30) *3;
        $pdf = PDF::loadView('sale::print-pos', ['sale' => $sale, 'barcode' => $barcodeUrl])
        ->setPaper([0, 0, 500, $heightMM], 'portrait');

        // Render PDF untuk mendapatkan output
        $output = $pdf->download();

        $filePath = storage_path('app/public/invoices/invoice_' . $sale->id . '.pdf');

        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        file_put_contents($filePath, $output);

        return response()->stream(
            function () use ($output) {
                echo $output;
            },
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="invoice.pdf"',
            ]
        );
    }

    public function index() {
        Cart::instance('sale')->destroy();

        $customers = Customer::where('business_id', Auth::user()->business_id)->get();
        $product_categories = Category::where('business_id', Auth::user()->business_id)->get();

        return view('sale::pos.index', compact('product_categories', 'customers'));
    }
    public function paymentFeeChange(request $request) {

        $paymentChannelData = PaymentChannel::find($request->id);
        $paymentFeePPN = 0;
        $applicationFee = 0;
        $amount = $request->amount;
        $paymentFee1 = 0;


        if ($paymentChannelData->fee_type_1 == '%'){
            $paymentFee1 = ($amount * $paymentChannelData->fee_value_1) / 100;
        }else
        {
            $paymentFee1 = $paymentChannelData->fee_value_1;
        }

        if ($paymentChannelData->fee_type_2 == '%'){
            $paymentFee2 = ($amount * $paymentChannelData->fee_value_2) / 100;
        }else
        {
            $paymentFee2 = $paymentChannelData->fee_value_2 ?? 0;
        }

        $dataConfigs = DataConfig::first();
        $paymentFee =$paymentFee1 + $paymentFee2 ;

        if ($paymentChannelData->is_ppn == true){
            $ratePPN = $dataConfigs->ppn_value;
            $paymentFeePPN = ($paymentFee * $ratePPN) / 100;
        }

        $applicationFee = $dataConfigs->app_fee_value;

        // if ($amount > 9999999){
        //     $applicationFee = $amount * 0.01;
        // }

        // if ($amount > 99999999){
        //     $applicationFee = $amount * 0.025;
        // }

        $grandTotal = $amount + $paymentFee + $applicationFee + $paymentFeePPN;

        return response()->json([
            'payment_fee' => $paymentFee + $paymentFeePPN,
            'payment_fee_masked' => format_currency($paymentFee + $paymentFeePPN),
            'payment_ppn_masked' => format_currency(0),
            'payment_ppn' => 0,
            'grand_total_masked' => format_currency($grandTotal),
            'grand_total' => $grandTotal,
            'application_fee_masked' => format_currency($applicationFee),
            'application_fee' => $applicationFee

        ]);
    }
    // public function paylaterPlans(
    //     request $request){
    //         $orderedProducts = [];
    //         $paymentGateway = new PaymentGatewayController();
    //         foreach (Cart::instance('sale')->content() as $cart_item) {
    //             $orderedProducts[] = [
    //                 'product_id' => $cart_item->id,
    //                 'product_name' => $cart_item->name,
    //                 'product_code' => $cart_item->options->code,
    //                 'quantity' => $cart_item->qty,
    //                 'price' => $cart_item->price,
    //                 'unit_price' => $cart_item->options->unit_price,
    //                 'sub_total' => $cart_item->options->sub_total,
    //                 'product_discount_amount' => $cart_item->options->product_discount,
    //                 'product_discount_type' => $cart_item->options->product_discount_type,
    //                 'product_tax_amount' => $cart_item->options->product_tax,
    //                 'business_id' => $request->user()->business_id,
    //             ];
    //         }

    //         $paylaterPlanResult = $paymentGateway->paylaterPlans(
    //             $request->customer_id,
    //             $orderedProducts,
    //             $request->channel_code,
    //             $request->total_amount);

    //         return response()->json(data: [
    //             'id' => $paylaterPlanResult->id ?? null,
    //             'plan_id' => $paylaterPlanResult->plan_id ?? null,
    //         ]);
    // }

    public function paymentFees(
    ?int $amount = 0,
    ?string $feeType1 = null,
    ?string $feeType2,
    ?float $feeValue1,
    ?float $feeValue2,
    ?bool $isPpn = false){

        if ($feeType1 == '%'){
            $paymentFee1 = ($amount * $feeValue1) / 100;
        }else
        {
            $paymentFee1 = $feeValue1;
        }

        if ($feeType2 == '%'){
            $paymentFee2 = ($amount * $feeValue2) / 100;
        }else
        {
            $paymentFee2 = $feeValue2;
        }


        $dataConfigs = DataConfig::first();

        if ($isPpn == true){
            $ratePPN = $dataConfigs->ppn_value;
            $paymentFeePPN = (($paymentFee1 + $paymentFee2) * $ratePPN) / 100;
        }

        $applicationFee = $dataConfigs->app_fee_value;

        if ($amount > 9999999){
            $applicationFee = $amount * 0.01;
        }

        if ($amount > 99999999){
            $applicationFee = $amount * 0.025;
        }

        $totalFees = $paymentFee1 + $paymentFee2 + $applicationFee + $paymentFeePPN;
        return [
            'totalFee'=>$totalFees,
            'paymentFee'=> $paymentFee1 + $paymentFee2,
            'applicationFee'=>$applicationFee,
            'paymentFeePpn'=>$paymentFeePPN,
        ];

    }
    public function createPaymentGatewayRequest(
        request $request){
        $valueResponse = null;
        $responseType = null;
        $nameResponse = null;
        $expireResponse = null;
        $valueQr = 'sample-barcode-text';
        $paymentChannelData = PaymentChannel::find($request->payment_channel_id);
        $reffPayment =  Str::orderedUuid()->toString() . '-' . Carbon::now()->format('Ymdss');
        $phoneNumber = $request->number_phone ?? null;
        $dataResult = null;
        $paymentRequestId = null;
        $paymentReferenceId = NULL;

        if ($paymentChannelData){

            if($paymentChannelData->type == 'VIRTUAL_ACCOUNT'){
                $createVirtualAccount = new PaymentGatewayController();

                $virtualAccountInfo = $createVirtualAccount->createVirtualAccount(
                    $reffPayment,
                    $paymentChannelData->code,
                    $request->amount,
                    $request->sale_amount
                );

                $paymentRequestId = $virtualAccountInfo['id'];
                $paymentReferenceId = $virtualAccountInfo['reference_id'];
                $virtualAccountInfo = $virtualAccountInfo['virtual_account'];
                $nameResponse = $virtualAccountInfo['name'];
                $valueResponse = $virtualAccountInfo['account_number'];
                $expResponseDate = $virtualAccountInfo['expiration_date'];
                $expireResponse = carbon::parse($expResponseDate)->setTimezone(config('app.timezone'))->format('d-m-Y H:m') ?? null;
                $responseType = 'account';

            }
            // elseif($paymentChannelData->type == 'QR_CODE'){
            //     $createQRCode = new PaymentGatewayController();

            //     $virtualAccountInfo = $createQRCode->createQRCode(
            //         $reffPayment,
            //         $paymentChannelData->code,
            //         $request->amount,
            //         $request->sale_amount
            //     );

            //     $paymentRequestId = $virtualAccountInfo['id'];
            //     $paymentReferenceId = $virtualAccountInfo['reference_id'];
            //     $virtualAccountInfo = $virtualAccountInfo['virtual_account'];
            //     $nameResponse = $virtualAccountInfo['name'];
            //     $valueResponse = $virtualAccountInfo['account_number'];
            //     $expResponseDate = $virtualAccountInfo['expiration_date'];
            //     $expireResponse = carbon::parse($expResponseDate)->setTimezone(config('app.timezone'))->format('d-m-Y H:m') ?? null;
            //     $responseType = 'account';

            // }
            elseif($paymentChannelData->type == 'INVOICE'){
                $totalOrderedAmounts = 0;

                $orderedProducts=[];

                $invoiceRequest = new PaymentGatewayController();
                $customerData = Customer::find($request->customer_id);

                $paymentChannelData = PaymentChannel::query()
                ->where('action','invoice_link')
                ->first();

                $paymentFee  = $this->paymentFees(
                    $request->sale_amount,
                    $paymentChannelData->fee_type_1,
                    $paymentChannelData->fee_type_2,
                    $paymentChannelData->fee_value_1 ?? 0,
                    $paymentChannelData->fee_value_2 ?? 0,
                    $paymentChannelData->is_ppn ?? false,
                );

                $expiryDate = Carbon::parse($request->invoice_expiry_date)->endOfDay();
                $now = Carbon::now();

                // Jika invoice_expiry_date adalah hari ini, set ke 1 hari (86,400 detik)
                if ($expiryDate->isToday()) {
                    $diffInSeconds = 86400; // 1 hari dalam detik
                } else {
                    // Jika lebih dari 1 hari, hitung selisih detik dari now() ke invoice_expiry_date
                    $diffInSeconds = $now->diffInSeconds($expiryDate, false) - 1;
                }

                $paymentMethodsData = ["CREDIT_CARD",
                    "BCA", "BNI", "BSI", "BRI", "MANDIRI", "PERMATA", "SAHABAT_SAMPOERNA", "BNC",
                    "ALFAMART", "INDOMARET",
                    "OVO", "DANA", "SHOPEEPAY", "LINKAJA", "JENIUSPAY",
                    "KREDIVO", "AKULAKU", "UANGME", "ATOME",
                    "QRIS"
                ];

                $dataResult = $invoiceRequest->createTransactionInvoiceRequest(
                    $customerData,
                    $orderedProducts,
                    $paymentMethodsData,
                    $paymentFee,
                    $request->amount + $paymentFee['totalFee'],
                    0,
                    0,
                    0,
                    $request->sale_amount,
                    $diffInSeconds,
                    true
                );

                $paymentRequestData = XenditCreatePayment::find($dataResult['id']);
                $invoiceRequestId = $dataResult['invoice_requests']['id'];
                $invoiceStatus = 'Pending';
                $invoiceUrl = $dataResult['invoice_requests']['invoice_url'];
                $expResponseDate = $dataResult['invoice_requests']['expiry_date'];
                $invoiceExpiryDate = Carbon::parse($expResponseDate)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s') ?? null;;

                $paymentRequestId = $dataResult['id'];
                $paymentReferenceId = $dataResult['reference_id'];
                $invoiceRequests = $dataResult['invoice_requests'];

                $nameResponse = $customerData->customer_name.'|'.$customerData->customer_phone.'|'.$customerData->customer_email ;
                $valueResponse = $invoiceRequests['invoice_url'];
                $expResponseDate = $invoiceRequests['expiry_date'];
                $expireResponse = carbon::parse($expResponseDate)->setTimezone(config('app.timezone'))->format('d-m-Y H:m') ?? null;
                $responseType = 'links';
            }
            elseif($paymentChannelData->type == 'CARD'){
                $invoiceRequest = new PaymentGatewayController();
                $customerData = Customer::find($request->customer_id);

                $orderedProducts = [];
                $totalOrderedAmounts = 0;
                $discountAmount = Cart::instance('sale')->discount();
                $taxAmount = Cart::instance('sale')->tax();
                $shippingAmount = $request->shipping_amount;
                foreach (Cart::instance('sale')->content() as $cart_item) {

                    $unitPrice = $cart_item->options->unit_price;

                    $orderedProducts[] = [
                        'product_id' => $cart_item->id,
                        'product_name' => $cart_item->name,
                        'product_code' => $cart_item->options->code,
                        'quantity' => $cart_item->qty,
                        'price' => $cart_item->price,
                        'unit_price' => $unitPrice,
                        'sub_total' => round($unitPrice) * $cart_item->qty,
                        'product_discount_amount' => $cart_item->options->product_discount,
                        'product_discount_type' => $cart_item->options->product_discount_type,
                        'product_tax_amount' => $cart_item->options->product_tax,
                        'business_id' => $request->user()->business_id,
                    ];
                    $totalOrderedAmounts +=  round($unitPrice) * $cart_item->qty;
                }

                $paymentFee  = $this->paymentFees(
                    $request->sale_amount,
                    $paymentChannelData->fee_type_1,
                    $paymentChannelData->fee_type_2,
                    $paymentChannelData->fee_value_1 ?? 0,
                    $paymentChannelData->fee_value_2 ?? 0,
                    $paymentChannelData->is_ppn ?? false,
                );
                $paymentMethodsData = ['CREDIT_CARD'];

                $dataResult = $invoiceRequest->createTransactionInvoiceRequest(
                    $customerData,
                    $orderedProducts,
                    $paymentMethodsData,
                    $paymentFee,
                    $request->amount,
                    $discountAmount ?? 0,
                    $taxAmount ?? 0,
                    $shippingAmount ?? 0,
                    $request->sale_amount);

                $paymentRequestId = $dataResult['id'];
                $paymentReferenceId = $dataResult['reference_id'];
                $invoiceRequests = $dataResult['invoice_requests'];

                $nameResponse = $customerData->customer_name.'|'.$customerData->customer_phone.'|'.$customerData->customer_email ;
                $valueResponse = $invoiceRequests['invoice_url'];
                $expResponseDate = $invoiceRequests['expiry_date'];
                $expireResponse = carbon::parse($expResponseDate)->setTimezone(config('app.timezone'))->format('d-m-Y H:m') ?? null;
                $responseType = 'links';

            }
            elseif($paymentChannelData->type == 'PAYLATER'){
                //start refer to xendit_paylater_request
                $paylaterPlan = new PaymentGatewayController();
                $customerData = Customer::find($request->customer_id);

                $orderedProducts = [];
                $totalOrderedAmounts = 0;
                $discountAmount = Cart::instance('sale')->discount();
                $taxAmount = Cart::instance('sale')->tax();
                $shippingAmount = $request->shipping_amount;

                foreach (Cart::instance('sale')->content() as $cart_item) {

                    $unitPrice = $cart_item->options->unit_price;

                    $orderedProducts[] = [
                        'product_id' => $cart_item->id,
                        'product_name' => $cart_item->name,
                        'product_code' => $cart_item->options->code,
                        'quantity' => $cart_item->qty,
                        'price' => $cart_item->price,
                        'unit_price' => $cart_item->options->unit_price,
                        'sub_total' => round($unitPrice),
                        'product_discount_amount' => $cart_item->options->product_discount,
                        'product_discount_type' => $cart_item->options->product_discount_type,
                        'product_tax_amount' => $cart_item->options->product_tax,
                        'business_id' => $request->user()->business_id,
                    ];
                    $totalOrderedAmounts +=  round($unitPrice ) * $cart_item->qty;
                }

                $paymentFee  = $this->paymentFees(
                    $request->sale_amount,
                    $paymentChannelData->fee_type_1,
                    $paymentChannelData->fee_type_2,
                    $paymentChannelData->fee_value_1 ?? 0,
                    $paymentChannelData->fee_value_2 ?? 0,
                    $paymentChannelData->is_ppn ?? false,
                );


                // $paymentChannelData = PaymentChannel::find($request->payment_channel);

                $paylaterPlanResult = $paylaterPlan->paylaterPlans(
                $request->customer_id,
                $paymentFee,
                $taxAmount  ?? 0,
                $discountAmount ?? 0,
                $shippingAmount ?? 0,
                $orderedProducts,
                $paymentChannelData,
                $request->amount,
                $request->sale_amount);

                $paylaterPlan = XenditPaylaterPlan::find($paylaterPlanResult['id']);

                $paymentGatewayController = new PaymentGatewayController();

                $dataResult = $paymentGatewayController->createPaylaterRequest(
                    planId: $paylaterPlan->plan_id,
                    refId: $reffPayment,
                    customerId: $request->customer_id,
                    xenditPaylaterPlanId: $paylaterPlanResult['id'],
                    saleAmount:$request->sale_amount
                );

                $paymentRequestId = $dataResult['id'];
                $paymentReferenceId = $dataResult['reference_id'];
                $paylaterRequest = $dataResult['paylater_requests'];


                $nameResponse = $customerData->customer_name.'|'.$customerData->customer_phone ;
                $paylaterRequestAction = json_decode($paylaterRequest['actions']);
                $valueResponse = $paylaterRequestAction->mobile_web_checkout_url;
                $expResponseDate = $paylaterRequest['expires_at'];
                $expireResponse = carbon::parse($expResponseDate)->setTimezone(config('app.timezone'))->format('d-m-Y H:m') ?? null;
                $responseType = 'direct';
            }else{
                //start refer to xendit_payment_request
                if ($paymentChannelData->code == 'OVO'){
                    if(Str::length($phoneNumber) <= 7){
                        throw new \Exception("Payment failed, Phone Number Error! " . "Check again ". $paymentChannelData->code . ' Numbers');
                    }
                }

                $paymentGatewayController = new PaymentGatewayController();

                $paymentResponse = $paymentGatewayController->createPaymentRequest(
                    refId: $reffPayment,
                    forUserId:null,
                    withSplitRule:null,
                    amount: $request->amount ,
                    saleAmount: $request->sale_amount ,
                    type:$paymentChannelData->type,
                    channelCode:$paymentChannelData->code,
                    reusability:'ONE_TIME_USE',
                    phoneNumber: $phoneNumber,
                    transactionalType: $request->transaction_type,
                );

                $responseArray = $paymentResponse->getData(true);
                $dataResult = $responseArray['data'];

                $paymentRequestId = $dataResult['id'];
                $paymentReferenceId = $dataResult['reference_id'];
                $paymentRequests = $dataResult['payment_requests'];


                $responseActions = json_decode($paymentRequests['actions'], true);
                if ($responseActions){
                    foreach ($responseActions as $item) {
                        if($paymentChannelData->type =='EWALLET'){
                            if (($paymentChannelData->code == 'ASTRAPAY') || ($paymentChannelData->code == 'LINKAJA') || ($paymentChannelData->code == 'DANA')){
                                if ($item['url_type']== 'MOBILE'){
                                    if (isset($item['qr_code']) && !is_null($item['qr_code'])) {
                                        $valueResponse = DNS2DFacade::getBarcodeHTML($item['qr_code'], 'QRCODE', 8,8 );
                                        $responseType = 'qrcode';
                                    }
                                    else{
                                        if (isset($item['url']) && !is_null($item['url'])){
                                            $valueResponse = $item['url'];
                                            $responseType = 'url';
                                        }else{
                                            throw new \Exception('Payment failed., Please try again');
                                        }
                                    }
                                }
                            }elseif($paymentChannelData->code == 'SHOPEEPAY'){
                                if ($item['action']== 'PRESENT_TO_CUSTOMER'){
                                    if (isset($item['qr_code']) && !is_null($item['qr_code'])) {
                                        $valueResponse = DNS2DFacade::getBarcodeHTML($item['qr_code'], 'QRCODE', 8,8 );
                                        $responseType = 'qrcode';
                                    }
                                    else{
                                        throw new \Exception('Payment failed., Please try again');
                                    }
                                }
                            }elseif($paymentChannelData->code == 'OVO'){
                                $valueResponse = "Please payment on Customer OVO's Account";
                                $responseType = 'info';
                            }else{
                                throw new \Exception("Payment failed, Payment Channel doesn't exist" . $paymentChannelData->code);
                            }
                        }else{
                            throw new \Exception("Payment failed, Payment Type doesn't exist for ". $paymentChannelData->type);
                        }
                    }
                }else{
                    $responseActions = json_decode($paymentRequests['payment_method'], true);
                    if ($responseActions){
                        if($paymentChannelData->type =='VIRTUAL_ACCOUNT'){
                            $nameResponse = $responseActions['virtual_account']['channel_properties']['customer_name'];
                            $valueResponse = $responseActions['virtual_account']['channel_properties']['virtual_account_number'];
                            $expResponseDate = $responseActions['virtual_account']['channel_properties']['expires_at'];
                            $expireResponse = carbon::parse($expResponseDate)->setTimezone(config('app.timezone'))->format('d-m-Y H:m') ?? null;
                            $responseType = 'account';
                        }elseif(($paymentChannelData->type =='EWALLET') && ($paymentChannelData->code =='OVO')){
                            $valueResponse = $responseActions['ewallet']['channel_properties']['mobile_number'];
                            $responseType = 'info';
                        }elseif($paymentChannelData->type =='QR_CODE'){
                            $valueResponse = DNS2DFacade::getBarcodeHTML($responseActions['qr_code']['channel_properties']['qr_string'], 'QRCODE', 8,8 );

                            $responseType = 'qrcode';
                        }else{
                            throw new \Exception('Payment failed., Please try again');
                        }
                    }
                }
                //end refer to xendit_payment_request
            }
        }

        return response()->json(data: [
            'payment_request_id' => $paymentRequestId ?? null,
            'reference_id' => $paymentReferenceId ?? null,
            'name_response' => $nameResponse ?? null,
            'value_response' => $valueResponse,
            'expired_response' => $expireResponse,
            'response_type' => $responseType,
            'nominal_information' => format_currency($request->amount),
        ]);
    }

    public function store(StorePosSaleRequest $request) {


        try{
            DB::transaction(function () use ($request) {
                $paymentRequestData = null;

                $due_amount = 0;//$request->total_amount - $request->paid_amount;

                if ($request->payment_channel){
                    if ($due_amount == $request->total_amount) {
                        $payment_status = 'Unpaid';
                    } elseif ($due_amount > 0) {
                        $payment_status = 'Partial';
                    } else {
                        $payment_status = 'waiting';
                    }
                }else{
                    if ($due_amount == $request->total_amount) {
                        $payment_status = 'Unpaid';
                    } elseif ($due_amount > 0) {
                        $payment_status = 'Partial';
                    } else {
                        $payment_status = 'Paid';
                    }
                }

                $paymentMethodData = PaymentMethod::find($request->payment_method);
                $paymentChannelData = PaymentChannel::find($request->payment_channel);
                $paymentChannelName = $paymentMethodData->name;
                if ($paymentChannelData){
                    $paymentRequestData = XenditCreatePayment::find($request->payment_id);
                    if ($paymentChannelData->type == 'VIRTUAL_ACCOUNT'){
                        $paymentChannelName = 'VA-'.$paymentChannelData->name;
                    }else{
                        $paymentChannelName =$paymentChannelData->name;
                    }
                }

                $sale = Sale::create([
                    'date' => now()->format('Y-m-d'),
                    'reference' => 'PSL',
                    'customer_id' => $request->customer_id,
                    'customer_name' => Customer::find($request->customer_id)->customer_name ?? null,
                    'tax_percentage' => $request->tax_percentage,
                    'discount_percentage' => $request->discount_percentage,
                    'shipping_amount' => $request->shipping_amount,
                    'paid_amount' => $request->amount_sale,
                    'additional_paid_amount' => $request->grand_total - $request->amount_sale,
                    'total_paid_amount' => $request->grand_total,
                    'total_amount' => $request->amount_sale,
                    'due_amount' => $due_amount,
                    'status' => 'Completed',
                    'payment_status' => $payment_status,
                    'payment_method' => $paymentChannelName,
                    'note' => $request->note,
                    'tax_amount' => Cart::instance('sale')->tax(),
                    'discount_amount' => Cart::instance('sale')->discount(),
                    'business_id' => $request->user()->business_id,
                ]);

                foreach (Cart::instance('sale')->content() as $cart_item) {
                    SaleDetails::create([
                        'sale_id' => $sale->id,
                        'product_id' => $cart_item->id,
                        'product_name' => $cart_item->name,
                        'product_code' => $cart_item->options->code,
                        'quantity' => $cart_item->qty,
                        'price' => $cart_item->price,
                        'unit_price' => $cart_item->options->unit_price,
                        'sub_total' => $cart_item->options->sub_total,
                        'product_discount_amount' => $cart_item->options->product_discount,
                        'product_discount_type' => $cart_item->options->product_discount_type,
                        'product_tax_amount' => $cart_item->options->product_tax,
                        'business_id' => $request->user()->business_id,
                    ]);

                    $product = Product::findOrFail($cart_item->id);
                    $product->update([
                        'product_quantity' => $product->product_quantity - $cart_item->qty
                    ]);
                }

                Cart::instance('sale')->destroy();

                if ($sale->paid_amount > 0) {


                    $refIdData = $paymentRequestData['reference_id'] ?? null;

                    SalePayment::create([
                        'date' => now()->format('Y-m-d'),
                        'reference' => 'sale/'.$sale->reference,
                        'reference_id' => $refIdData,
                        'amount' => $sale->total_amount,
                        'sale_id' => $sale->id,
                        'payment_method' => $paymentMethodData->name,
                        'payment_method_id' => $paymentMethodData->id,
                        'payment_method_name' => $paymentMethodData->name,
                        'payment_channel_id' => $paymentChannelData->id ?? null,
                        'payment_channel_name' => $paymentChannelName ?? null,
                        'business_id' => $request->user()->business_id,
                        'xendit_create_payment_id' => $paymentRequestData['id'] ?? null,
                    ]);

                    $paymentMethod = XenditPaymentMethod::query()
                    ->where('reference_id', $refIdData)
                    ->first();

                    if ($paymentMethod) {
                        $paymentMethod->transactional_id = $sale->id;
                        $paymentMethod->save();
                    }

                    if ($paymentRequestData) {
                        $paymentRequestData->source_type = Sale::class;
                        $paymentRequestData->source_id = $sale->id;
                        $paymentRequestData->save();
                    }
                }
            });



        toast(__('controller.created'), 'success');

        }catch(Exception $e){
            toast($e->getMessage(), 'error');
        }

        return redirect()->route('app.pos.index');

    }
}
