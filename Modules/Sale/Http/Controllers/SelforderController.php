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

            Cart::instance('mobile-order')->destroy();
            return view('sale::selforder.mobileorder', compact( 'customers', 'selforderBusiness', 'business'));
        }

    public function indexSelforderCheckout(SelforderCheckoutDataTable $dataTable) {

        return $dataTable->render('sale::selforder.ordered.lists.mobileorder');
    }
    public function selforderProcess()
    {

        return view('sale::selforder.selforderprocess');

    }

    public function selforderCheckout(SelforderCheckout $selforderCheckout)
    {
        $customer = Customer::find($selforderCheckout->customer_id) ?? null;

        return view('sale::selforder.ordered.show.mobileorder', compact('selforderCheckout', 'customer'));

        //ini nanti diarahkan ke selforderCheckout Preview Detail
        // return view('sale::selforder.ordered.mobileorder',compact( ['selforderCheckout']));

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
        // try{
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

                $selforderCheckout = SelforderCheckout::create([
                    'selforder_business_id' => $request->selforder_business_id,
                    'business_id' => $business->id,
                    'business_name' => $business->name,
                    'date' => now()->format('Y-m-d'),
                    'reference' => 'SOM',
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
                    'tax_amount' => Cart::instance('mobile-order')->tax(),
                    'discount_amount' => Cart::instance('mobile-order')->discount(),
                ]);

                foreach (Cart::instance('mobile-order')->content() as $cart_item) {
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

                Cart::instance('mobile-order')->destroy();

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


            $link = route('selfordercheckout', ['selforderCheckout' => $selforderCheckoutId]);
            $qrCodePng = DNS2DFacade::getBarcodePNG($link, 'QRCODE', 8,8 );
            $qrImage = Image::make(base64_decode($qrCodePng));
            $margin = 20;
            $canvasWidth = $qrImage->width() + ($margin * 2);
            $canvasHeight = $qrImage->height() + ($margin * 2);
            $canvas = Image::canvas($canvasWidth, $canvasHeight, '#ffffff');
            $canvas->insert($qrImage, 'center');
            $qrCode = base64_encode($canvas->encode('png'));

            return view('sale::selforder.ordered.mobileorder',compact( ['qrCode', 'link']));



        // }catch(Exception $e){
        //     toast($e->getMessage(), 'error');
        // }
        //Redirect to Barcode Summaries
        // return redirect()->route('app.pos.index');

    }
}
