<?php

namespace Modules\Quotation\Http\Controllers;

use App\Models\DataConfig;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Modules\PaymentGateway\Entities\XenditCreatePayment;
use Modules\PaymentGateway\Http\Controllers\PaymentGatewayController;
use Modules\PaymentMethod\Entities\PaymentChannel;
use Modules\People\Entities\Customer;
use Modules\Product\Entities\Product;
use Modules\Quotation\DataTables\QuotationsDataTable;
use Modules\Quotation\Entities\Quotation;
use Modules\Quotation\Entities\QuotationDetails;
use Modules\Quotation\Http\Requests\StoreQuotationRequest;
use Modules\Quotation\Http\Requests\UpdateQuotationRequest;

class QuotationController extends Controller
{

    public function index(QuotationsDataTable $dataTable) {
        abort_if(Gate::denies('access_quotations'), 403);

        return $dataTable->render('quotation::index');
    }

    public function create() {
        abort_if(Gate::denies('create_quotations'), 403);

        Cart::instance('quotation')->destroy();

        return view('quotation::create');
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

            $totalFees = $paymentFee1 + $paymentFee2 + $applicationFee + $paymentFeePPN;
            return [
                'totalFee'=>$totalFees,
                'paymentFee'=> $paymentFee1 + $paymentFee2,
                'applicationFee'=>$applicationFee,
                'paymentFeePpn'=>$paymentFeePPN,
            ];

        }
    public function store(StoreQuotationRequest $request) {
        DB::transaction(function () use ($request) {
            $invoiceRequestId = null;
            $invoiceStatus = null;
            $invoiceUrl = null;
            $invoiceExpiryDate = null;
            $paymentRequestData=null;
            $withInvoice = $request->has('with_invoice') ? true : false;

            $discountAmount = Cart::instance('quotation')->discount();

            if ($withInvoice){

                $totalOrderedAmounts = 0;
                foreach (Cart::instance('quotation')->content() as $cart_item) {

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
                    $request->total_amount + $paymentFee['totalFee'],
                    $discountAmount,
                    $request->total_amount,
                    $diffInSeconds,
                    false
                );

                $paymentRequestData = XenditCreatePayment::find($dataResult['id']);
                $invoiceRequestId = $dataResult['invoice_requests']['id'];
                $invoiceStatus = 'Pending';
                $invoiceUrl = $dataResult['invoice_requests']['invoice_url'];
                $expResponseDate = $dataResult['invoice_requests']['expiry_date'];
                $invoiceExpiryDate = Carbon::parse($expResponseDate)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s') ?? null;;

            }

            $quotation = Quotation::create([
                'date' => $request->date,
                'customer_id' => $request->customer_id,
                'customer_name' => Customer::findOrFail($request->customer_id)->customer_name,
                'tax_percentage' => $request->tax_percentage,
                'discount_percentage' => $request->discount_percentage,
                'shipping_amount' => $request->shipping_amount,
                'total_amount' => $request->total_amount,
                'status' => $request->status,
                'note' => $request->note,
                'with_invoice' => $withInvoice,
                'xendit_invoice_request_id' => $invoiceRequestId,
                'invoice_status' => $invoiceStatus,
                'invoice_url' => $invoiceUrl,
                'invoice_expiry_date' => $invoiceExpiryDate,
                'tax_amount' => Cart::instance('quotation')->tax(),
                'discount_amount' => Cart::instance('quotation')->discount(),
                'business_id' => $request->user()->business_id
            ]);



            if ($paymentRequestData) {
                $paymentRequestData->source_type = Quotation::class;
                $paymentRequestData->source_id = $quotation->id;
                $paymentRequestData->save();
            }

            foreach (Cart::instance('quotation')->content() as $cart_item) {
                QuotationDetails::create([
                    'quotation_id' => $quotation->id,
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
            }

            Cart::instance('quotation')->destroy();

            if ($request->status == 'Sent'){
                $quotationMail = new SendQuotationEmailController();
                $quotationMail($quotation);
                toast('Quotation Created & Sent to '. $quotation->customer->customer_email, 'success');
            }else{
                toast('Quotation Created!', 'success');
            }
        });



        return redirect()->route('quotations.index');
    }


    public function show(Quotation $quotation) {
        abort_if(Gate::denies('show_quotations'), 403);

        $customer = Customer::findOrFail($quotation->customer_id);

        return view('quotation::show', compact('quotation', 'customer'));
    }


    public function edit(Quotation $quotation) {
        abort_if(Gate::denies('edit_quotations'), 403);

        $quotation_details = $quotation->quotationDetails;

        Cart::instance('quotation')->destroy();

        $cart = Cart::instance('quotation');

        foreach ($quotation_details as $quotation_detail) {
            $cart->add([
                'id'      => $quotation_detail->product_id,
                'name'    => $quotation_detail->product_name,
                'qty'     => $quotation_detail->quantity,
                'price'   => $quotation_detail->price,
                'weight'  => 1,
                'options' => [
                    'product_discount' => $quotation_detail->product_discount_amount,
                    'product_discount_type' => $quotation_detail->product_discount_type,
                    'sub_total'   => $quotation_detail->sub_total,
                    'code'        => $quotation_detail->product_code,
                    'stock'       => Product::findOrFail($quotation_detail->product_id)->product_quantity,
                    'product_tax' => $quotation_detail->product_tax_amount,
                    'unit_price'  => $quotation_detail->unit_price
                ]
            ]);
        }

        return view('quotation::edit', compact('quotation'));
    }


    public function update(UpdateQuotationRequest $request, Quotation $quotation) {
        DB::transaction(function () use ($request, $quotation) {
            foreach ($quotation->quotationDetails as $quotation_detail) {
                $quotation_detail->delete();
            }

            $quotation->update([
                'date' => $request->date,
                'reference' => $request->reference,
                'customer_id' => $request->customer_id,
                'customer_name' => Customer::findOrFail($request->customer_id)->customer_name,
                'tax_percentage' => $request->tax_percentage,
                'discount_percentage' => $request->discount_percentage,
                'shipping_amount' => $request->shipping_amount,
                'total_amount' => $request->total_amount,
                'status' => $request->status,
                'note' => $request->note,
                'tax_amount' => Cart::instance('quotation')->tax(),
                'discount_amount' => Cart::instance('quotation')->discount(),
            ]);

            foreach (Cart::instance('quotation')->content() as $cart_item) {
                QuotationDetails::create([
                    'quotation_id' => $quotation->id,
                    'business_id' => Auth::user()->business_id,
                    'product_id' => $cart_item->id,
                    'product_name' => $cart_item->name,
                    'product_code' => $cart_item->options->code,
                    'quantity' => $cart_item->qty,
                    'price' => $cart_item->price,
                    'unit_price' => $cart_item->options->unit_price,
                    'sub_total' => $cart_item->options->sub_total,
                    'product_discount_amount' => $cart_item->options->product_discount_amount ?? 0,
                    'product_discount_type' => $cart_item->options->product_discount_type,
                    'product_tax_amount' => $cart_item->options->product_tax,
                ]);
            }

            Cart::instance('quotation')->destroy();
        });

        toast('Quotation Updated!', 'info');

        return redirect()->route('quotations.index');
    }


    public function destroy(Quotation $quotation) {
        abort_if(Gate::denies('delete_quotations'), 403);

        $quotation->delete();

        toast('Quotation Deleted!', 'warning');

        return redirect()->route('quotations.index');
    }
}
