<?php

namespace Modules\Sale\Http\Controllers;

use App\Helpers\PhoneHelper;
use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DataConfig;
use App\Models\masterConfig;
use App\Models\MasterConfig as ModelsMasterConfig;
use Carbon\Carbon;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Support\Renderable;
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
use Modules\Sale\Entities\SelforderBusiness;
use Modules\Sale\Entities\SelforderCheckout;
use Modules\Sale\Entities\SelforderCheckoutPayment;
use Modules\Sale\Entities\SelforderType;
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
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

use Intervention\Image\ImageManagerStatic as Image;
use Modules\Sale\DataTables\SelforderCheckoutDataTable;
use Modules\Sale\Entities\SelforderCheckoutDetail;

class SelforderController extends Controller
{
    /**
     * Display a listing of the resource.
     */

        public function posMobileOrder(string $id, request $request)
        {
            $request->validate([
                'first_name'  => 'required|string|max:255',
                'last_name'  => 'required|string|max:255',
                'phone_number' => 'required|max:255',
                'email' => 'required|email|max:255',
                'gender'           => 'required|string|max:255',
            ]);

            $formattedPhone = PhoneHelper::formatToE164Indonesia($request->phone_number);

            $selforderBusiness = SelforderBusiness::find($id);
            $business = Business::find($selforderBusiness->business_id);
            $customers = Customer::query()
            ->where('business_id' , $selforderBusiness->business_id)
            ->where('customer_phone' , $formattedPhone)
            ->first();

            if (blank($customers)){
                $customers = Customer::create(
                    [
                        'customer_first_name' => $request->first_name,
                        'customer_last_name' => $request->last_name,
                        'customer_name' => $request->first_name . ' ' . $request->last_name,
                        'customer_phone' => $formattedPhone,
                        'customer_email' => $request->email,
                        'gender' => $request->gender,
                        'business_id' => $selforderBusiness->business_id

                    ]
                );
            }


            // $customerId = $customers->id;
            // // Cart::instance($customers->id)->destroy();
            // if (session()->has("cart_{'$customerId'}")) {
            //     // Kembalikan isi cart dari sesi jika sudah ada
            //     // Cart::instance($customers->id)->restore(session("cart_{'$customers->id'}"));

            //     Cart::instance($customers->id)->restore(session("cart_{'$customerId'}"));
            // } else {
            //     // Jika belum ada, buat instance cart baru
            //     Cart::instance($customers->id);
            // }

            // Simpan instance cart ke sesi
            // session(["cart_{$customerId}" => Cart::instance($customerId)->content()]);
            // php artisan vendor:publish --    provider="Gloudemans\Shoppingcart\ShoppingcartServiceProvider" --tag="migrations"


            return view('sale::selforder.mobileorder', compact( 'customers', 'selforderBusiness', 'business'));
        }

    public function indexSelforderCheckout(SelforderCheckoutDataTable $dataTable) {

        return $dataTable->render('sale::selforder.ordered.lists.mobileorder');
    }

    public function calculateRoute(Request $request)
    {
        // Ambil input latitude dan longitude dari permintaan
        $fromLat = $request->input('from_lat');
        $fromLng = $request->input('from_lng');
        $toLat = $request->input('to_lat');
        $toLng = $request->input('to_lng');

        // URL API OpenRouteService
        $url = 'https://api.openrouteservice.org/v2/directions/driving-car';

        // Data yang akan dikirim ke API dalam format JSON
        $data = [
            'coordinates' => [
                [$fromLng, $fromLat], // Titik awal (longitude, latitude)
                [$toLng, $toLat]      // Titik tujuan (longitude, latitude)
            ]
        ];

        // Inisialisasi cURL
        $ch = curl_init($url);

        // Konfigurasi opsi cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: 5b3ce3597851110001cf62484495a59384c04dca8ce3521b11ac8cac'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Eksekusi permintaan
        $response = curl_exec($ch);

        // Cek error
        if (curl_errno($ch)) {
            return response()->json(['error' => curl_error($ch)], 500);
        }

        curl_close($ch);

        // Decode JSON response
        $responseData = json_decode($response, true);

        // Ambil data jarak dalam kilometer (nilai default dalam meter)
        $distanceInMeters = $responseData['routes'][0]['segments'][0]['distance'] ?? null;
        $distanceInKm = intval(ceil($distanceInMeters)) ? round(ceil($distanceInMeters) / 1000, 0, PHP_ROUND_HALF_UP) : null;

        return response()->json([
            'distance_km' => $distanceInKm,
            'raw_response' => $responseData
        ]);
    }
    public function selforderProcess()
    {

        return view('sale::selforder.selforderprocess');

    }

    public function selforderCheckout(SelforderCheckout $selforderCheckout)
    {


        $customer = Customer::find($selforderCheckout->customer_id) ?? null;

        $selforderCheckout_details = $selforderCheckout->selforderCheckoutDetails;

        $selforderCheckout_Payment = $selforderCheckout->selforderCheckoutPayments;

        $selforderType = $selforderCheckout->selforderBusiness->selforderType;
        Cart::instance('sale')->destroy();

        $cart = Cart::instance('sale');

        foreach ($selforderCheckout_details as $selforderCheckout_detail) {
            $cart->add([
                'id'      => $selforderCheckout_detail->product_id,
                'name'    => $selforderCheckout_detail->product_name,
                'qty'     => $selforderCheckout_detail->quantity,
                'price'   => $selforderCheckout_detail->price,
                'weight'  => 1,
                'options' => [
                    'product_discount' => $selforderCheckout_detail->product_discount_amount,
                    'product_discount_type' => $selforderCheckout_detail->product_discount_type,
                    'sub_total'   => $selforderCheckout_detail->sub_total,
                    'code'        => $selforderCheckout_detail->product_code,
                    'stock'       => Product::findOrFail($selforderCheckout_detail->product_id)->product_quantity,
                    'product_tax' => $selforderCheckout_detail->product_tax_amount,
                    'unit_price'  => $selforderCheckout_detail->unit_price
                ]
            ]);
        }

        return view('sale::selforder.ordered.show.mobileorder', [
            'selforder_checkout_id' => $selforderCheckout->id,
            'sale' => $selforderCheckout,
            'payment' => $selforderCheckout_Payment,
            'customer' => $customer,
            'selforder_type' => $selforderType
        ]);

        // return view('quotation::quotation-sales.create', [
        //     'quotation_id' => $quotation->id,
        //     'sale' => $quotation
        // ]);

        // return view('sale::selforder.ordered.show.mobileorder', compact('selforderCheckout', 'customer'));

        //ini nanti diarahkan ke selforderCheckout Preview Detail
        // return view('sale::selforder.ordered.mobileorder',compact( ['selforderCheckout']));

    }
    public function enterSelforder()
    {
            return view('sale::enterselforder');
    }

    public function indexMobileOrder(string $business, string $key)
    {

        $key = decryptWithKey($key);

        $date = Carbon::now();
        $formattedDate = $date->format('dmY');

        if ($key != $formattedDate){
            return view('sale::selforder-expired', );
        }
        else{
            // toast(__('Welcome and Happy Shopping!'), 'Success');
            return view('sale::entermobileorder',compact( 'business'));
        }

    }

    public function mobileOrderQrCodeGenerator(string $id)
    {
        $date = Carbon::now();
        $formattedDate = $date->format('dmY');
        $key = encryptWithKey($formattedDate);

        $selforderType = SelforderType::query()
            ->where('code','mobileorder')
            ->first();

        $selforderBusiness = SelforderBusiness::query()
        ->where('business_id',$id)
        ->where('selforder_type_id',$selforderType->id)
        ->first();

        $businessData = Business::find($id);

        $link = route('selforder.indexMobileOrder', ['business' => $selforderBusiness->id, 'key' => $key]);
        $qrCodePng = DNS2DFacade::getBarcodePNG($link, 'QRCODE', 8,8 );
        $qrImage = Image::make(base64_decode($qrCodePng));
        $margin = 20;
        $canvasWidth = $qrImage->width() + ($margin * 2);
        $canvasHeight = $qrImage->height() + ($margin * 2);
        $canvas = Image::canvas($canvasWidth, $canvasHeight, '#ffffff');
        $canvas->insert($qrImage, 'center');
        $qrCode = base64_encode($canvas->encode('png'));
        return view('sale::mobileorder-qrgenerator', compact( ['selforderBusiness','qrCode', 'businessData','link']));
    }
    public function manageDeliveryOrder() {

        // $selforderBusiness = null;
        return view('sale::selforder-delivery');
    }
    public function manageMobileOrder() {
    // abort_if(Gate::denies('edit_products'), 403);

        $business = Business::find(Auth::user()->business_id);

        $selforderType = SelforderType::query()
        ->where('code','mobileorder')
        ->first();

        $selforderBusiness = SelforderBusiness::query()
        ->where('business_id',$business->id)
        ->where('selforder_type_id',$selforderType->id)
        ->first();

        $date = Carbon::now();
        $formattedDate = $date->format('dmY');
        $key = encryptWithKey($formattedDate);
        $link = route('selforder.indexMobileOrder', ['business' => $selforderBusiness->id, 'key' => $key]);

        $qrCodePng = DNS2DFacade::getBarcodePNG($link, 'QRCODE', 100,100 );
        $qrImage = Image::make(base64_decode($qrCodePng));

        $margin = 20; // Margin dalam piksel
        $canvasWidth = $qrImage->width() + ($margin * 2); // Tambah margin kiri dan kanan
        $canvasHeight = $qrImage->height() + ($margin * 2); // Tambah margin atas dan bawah
        $canvas = Image::canvas($canvasWidth, $canvasHeight, '#ffffff');
        $canvas->insert($qrImage, 'center');
        $qrCode = base64_encode($canvas->encode('png'));
        $url = route('selforder.mobileOrderQrCodeGenerator', ['id' => $business->id]);

        // $selforderBusiness = null;
        return view('sale::selforder-mobile', compact(['selforderType','qrCode','url','selforderBusiness','business','link']));
    }
    public function updateSelforderBusinessMobile($id, request $request){
        $request->validate([
            'subject' => 'required|string|min:5',
            'captions' => 'required|string|min:5',
            'status' => 'required|boolean',
        ]);

        $selforderType = SelforderType::query()
        ->where('code','mobileorder')
        ->first();

        SelforderBusiness::updateOrCreate(
            ['business_id' => $id,
            'selforder_type_id' => $selforderType->id],
            [
                'subject' => $request->subject,
                'captions' => $request->captions,
                'status' => $request->status,
                'need_customers' => true,
            ]
        );

        toast(__('selforder.business.info_updated'), 'success');
        return $this->manageMobileOrder();

    }
    public function storeMobileOrder(request $request) {
        $request->validate([
            'customer_id' => 'nullable|string',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'shipping_amount' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'note' => 'nullable|string|max:1000'
        ]);


        $selforderCheckoutId = null;
        try{
            // DB::transaction(function () use ($request) {
                $paymentRequestData = null;

                $due_amount = 0;//$request->total_amount - $request->paid_amount;

                if ($request->payment_channel){
                    if ($due_amount == $request->total_amount) {
                        $payment_status = 'Unpaid';
                    } elseif ($due_amount > 0) {
                        $payment_status = 'Partial';
                    } else {
                        $payment_status = 'Waiting';
                    }
                }else{
                    // if ($due_amount == $request->total_amount) {
                        $payment_status = 'Unpaid';
                    // } elseif ($due_amount > 0) {
                    //     $payment_status = 'Partial';
                    // } else {
                    //     $payment_status = 'Paid';
                    // }
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

                $selforderBusiness = SelforderBusiness::find($request->selforder_business_id);
                $business = Business::find($selforderBusiness->business_id);

                $selforderCheckout = SelforderCheckout::query()->where('business_id',$business->id)->count();
                $referenceNumber = 'SOM' . str_pad($selforderCheckout, 10, '0', STR_PAD_LEFT);
                $selforderCheckout = SelforderCheckout::create([
                    'selforder_business_id' => $request->selforder_business_id,
                    'business_id' => $business->id,
                    'business_name' => $business->name,
                    'date' => now()->format('Y-m-d'),
                    'reference' => $referenceNumber,
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
                    'tax_amount' => Cart::instance($request->customer_id)->tax(),
                    'discount_amount' => Cart::instance($request->customer_id)->discount(),
                ]);
                foreach (Cart::instance($request->customer_id)->content() as $cart_item) {
                    SelforderCheckoutDetail::create([
                        'selforder_checkout_id' => $selforderCheckout->id,
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
                        'business_id' => $business->id,
                    ]);

                }



                DB::table('shoppingcart')->where('identifier', "cart_{$request->customer_id}")->delete();

                if ($selforderCheckout->paid_amount > 0) {

                    $refIdData = $paymentRequestData['reference_id'] ?? null;

                    SelforderCheckoutPayment::create([
                        'selforder_checkout_id' => $selforderCheckout->id,
                        'payment_method' => $paymentMethodData->name,
                        'payment_method_name' => $paymentMethodData->name,
                        'payment_method_id' => $paymentMethodData->id,
                        'xendit_create_payment_id' => $paymentRequestData['id'] ?? null,
                        'payment_channel_id' => $paymentChannelData->id ?? null,
                        'payment_channel_name' => $paymentChannelName ?? null,
                        'amount' => $selforderCheckout->total_amount,
                        'date' => now()->format('Y-m-d'),
                        'reference' => 'SelforderMobile/'.$selforderCheckout->reference,
                        'reference_id' => $refIdData,
                        'business_id' => $business->id,
                    ]);

                    $paymentMethod = XenditPaymentMethod::query()
                    ->where('reference_id', $refIdData)
                    ->first();

                    if ($paymentMethod) {
                        $paymentMethod->transactional_type = SelforderCheckout::class;
                        $paymentMethod->transactional_id = $selforderCheckout->id;
                        $paymentMethod->save();
                    }

                    if ($paymentRequestData) {
                        $paymentRequestData->source_type = SelforderCheckout::class;
                        $paymentRequestData->source_id = $selforderCheckout->id;
                        $paymentRequestData->save();
                    }

                    $selforderCheckoutId = $selforderCheckout->id;

                    // dd($selforderCheckoutId);
                    }
            // });


            // return response()->json([
            //     'selforder_checkout_id' => $selforderCheckoutId
            // ]);

            $selforder_checkout_id = $selforderCheckoutId;
            $link = route('selfordercheckout', ['selforderCheckout' => $selforderCheckoutId]);
            $qrCodePng = DNS2DFacade::getBarcodePNG($link, 'QRCODE', 8,8 );
            $qrImage = Image::make(base64_decode($qrCodePng));
            $margin = 20;
            $canvasWidth = $qrImage->width() + ($margin * 2);
            $canvasHeight = $qrImage->height() + ($margin * 2);
            $canvas = Image::canvas($canvasWidth, $canvasHeight, '#ffffff');
            $canvas->insert($qrImage, 'center');
            $qrCode = base64_encode($canvas->encode('png'));


                // Jika requestnya AJAX, kembalikan JSON
            if (request()->ajax()) {
                return response()->json([
                    'selforder_checkout_id' => $selforderCheckoutId,
                    'qrCode' => $qrCode,
                    'link' => $link,
                ]);
            }
            Cart::instance($selforderCheckout->customer_id)->destroy();
             return view('sale::selforder.ordered.mobileorder',compact( ['qrCode', 'link','selforder_checkout_id']))->render();

        }catch(Exception $e){
            toast($e->getMessage(), 'error');
        }

    }
    public function redirectSuccessSelfOrder($id){

        $selforderCheckout = SelforderCheckout::find($id);
        $selforder_checkout_id = $id;
        $link = route('selfordercheckout', ['selforderCheckout' => $id]);
        $qrCodePng = DNS2DFacade::getBarcodePNG($link, 'QRCODE', 8,8 );
        $qrImage = Image::make(base64_decode($qrCodePng));
        $margin = 20;
        $canvasWidth = $qrImage->width() + ($margin * 2);
        $canvasHeight = $qrImage->height() + ($margin * 2);
        $canvas = Image::canvas($canvasWidth, $canvasHeight, '#ffffff');
        $canvas->insert($qrImage, 'center');
        $qrCode = base64_encode($canvas->encode('png'));

        Cart::instance($selforderCheckout->customer_id)->destroy();
        return view('sale::selforder.ordered.mobileorder',compact( ['qrCode', 'link','selforder_checkout_id']));

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

            $customerData = Customer::find($request->customer_id);
            $selforderBusiness = SelforderBusiness::find($request->selforder_business_id);
            $business = Business::find($selforderBusiness->business_id);

            if($paymentChannelData->type == 'VIRTUAL_ACCOUNT'){
                $createVirtualAccount = new PaymentGatewayController();

                $virtualAccountInfo = $createVirtualAccount->createVirtualAccount(
                    $reffPayment,
                    $paymentChannelData->code,
                    $request->amount,
                    $request->sale_amount,
                    $business->id
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
            elseif($paymentChannelData->type == 'INVOICE'){
                $totalOrderedAmounts = 0;

                $orderedProducts=[];

                $invoiceRequest = new PaymentGatewayController();

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
                    true,
                    $business->id
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

                $orderedProducts = [];
                $totalOrderedAmounts = 0;
                $discountAmount = Cart::instance($request->customer_id)->discount();
                $taxAmount = Cart::instance($request->customer_id)->tax();
                $shippingAmount = $request->shipping_amount;
                foreach (Cart::instance($request->customer_id)->content() as $cart_item) {

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
                        'business_id' => $business->id,
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
                    $request->sale_amount,3600,true,
                    $business->id);

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

                $orderedProducts = [];
                $totalOrderedAmounts = 0;
                $discountAmount = Cart::instance($request->customer_id)->discount();
                $taxAmount = Cart::instance($request->customer_id)->tax();
                $shippingAmount = $request->shipping_amount;

                foreach (Cart::instance($request->customer_id)->content() as $cart_item) {

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
                        'business_id' => $business->id,
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
                    saleAmount:$request->sale_amount,
                    businessId:$business->id
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
                    businessId: $business->id
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
}
