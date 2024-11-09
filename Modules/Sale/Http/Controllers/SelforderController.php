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

            $formattedPhone = PhoneHelper::formatToE164Indonesia($request->phone);

            $customer = Customer::query()
            ->where('business_id' , $id)
            ->where('customer_phone' , $formattedPhone)
            ->first();

            if (blank($customer)){
                $customers = Customer::create(
                    [
                        'customer_first_name' => $request->first_name,
                        'customer_last_name' => $request->last_name,
                        'customer_name' => $request->first_name . ' ' . $request->last_name,
                        'customer_phone' => $request->phone_number,
                        'customer_email' => $request->email,
                        'gender' => $request->gender,
                        'business_id' => $id

                    ]
                );
            }

            return view('sale::selforder.mobileorder', compact( 'customers'));
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
            toast(__('Welcome and Happy Shopping!'), 'Success');
            return view('sale::entermobileorder',compact( 'business'));
        }

    }

    public function mobileOrderQrCodeGenerator(string $id)
    {
        $date = Carbon::now();
        $formattedDate = $date->format('dmY');
        $key = decryptWithKey($formattedDate);

        $selforderType = SelforderType::query()
            ->where('code','mobileorder')
            ->first();

        $selforderBusiness = SelforderBusiness::query()
        ->where('business_id',$id)
        ->where('selforder_type_id',$selforderType->id)
        ->first();

        $businessData = Business::find($id);

        $link = route('selforder.indexMobileOrder', ['business' => $id, 'key' => $key]);
        $qrCodePng = DNS2DFacade::getBarcodePNG($link, 'QRCODE', 8,8 );
        $qrImage = Image::make(base64_decode($qrCodePng));
        $margin = 20;
        $canvasWidth = $qrImage->width() + ($margin * 2);
        $canvasHeight = $qrImage->height() + ($margin * 2);
        $canvas = Image::canvas($canvasWidth, $canvasHeight, '#ffffff');
        $canvas->insert($qrImage, 'center');
        $qrCode = base64_encode($canvas->encode('png'));
        return view('sale::mobileorder-qrgenerator', compact( ['selforderBusiness','qrCode', 'businessData']));
    }
    public function manageMobileOrder() {
    // abort_if(Gate::denies('edit_products'), 403);
        $selforderType = SelforderType::query()
        ->where('code','mobileorder')
        ->first();
        $date = Carbon::now();
        $formattedDate = $date->format('dmY');
        $key = encryptWithKey($formattedDate);
        $business = Business::find(Auth::user()->business_id);
        $link = route('selforder.indexMobileOrder', ['business' => $business->id, 'key' => $key]);

        $qrCodePng = DNS2DFacade::getBarcodePNG($link, 'QRCODE', 100,100 );
        $qrImage = Image::make(base64_decode($qrCodePng));

        $margin = 20; // Margin dalam piksel
        $canvasWidth = $qrImage->width() + ($margin * 2); // Tambah margin kiri dan kanan
        $canvasHeight = $qrImage->height() + ($margin * 2); // Tambah margin atas dan bawah
        $canvas = Image::canvas($canvasWidth, $canvasHeight, '#ffffff');
        $canvas->insert($qrImage, 'center');
        $qrCode = base64_encode($canvas->encode('png'));
        $url = route('selforder.mobileOrderQrCodeGenerator', ['id' => $business->id]);

        $selforderBusiness = SelforderBusiness::query()
        ->where('business_id',$business->id)
        ->where('selforder_type_id',$selforderType->id)
        ->first();
        // $selforderBusiness = null;
        return view('sale::selforder-mobile', compact(['selforderType','qrCode','url','selforderBusiness','business']));
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

    public function create()
    {
        return view('sale::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request): RedirectResponse
    // {
    //     //
    // }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('sale::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('sale::edit');
    }

    /**
     * Update the specified resource in storage.
     */
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
}
