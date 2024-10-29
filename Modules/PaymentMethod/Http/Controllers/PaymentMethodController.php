<?php

namespace Modules\PaymentMethod\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\PaymentGateway\Http\Controllers\PaymentGatewayController;
use Modules\PaymentMethod\Entities\PaymentChannel;
use Modules\PaymentMethod\Entities\PaymentMethod;
use Modules\Sale\Http\Controllers\PosController;
use Str;

class PaymentMethodController extends Controller
{

    public function getAllPaymentMethod()
    {
        $paymentMethod = PaymentMethod::where('status',true)->get();

        if ($paymentMethod->count() > 0) {
            return response()->json($paymentMethod);
        } else {
            return response()->json(null);
        }

    }
    public function getPaymentMethod(string $id)
    {
        $paymentMethod = PaymentMethod::find($id);

        return response()->json($paymentMethod);


    }
    public function getPaymentChannel(string $id)
    {
        $paymentMethod = PaymentChannel::find($id);

        return response()->json($paymentMethod);


    }
    public function getPaymentChannels(Request $request)
    {
        $paymentMethodId = $request->payment_method;
        $paymentChannels = PaymentChannel::where('payment_method_id', $paymentMethodId)->where('status',true)->get();

        if ($paymentChannels->count() > 0) {
            return response()->json($paymentChannels);
        } else {
            return response()->json(null);
        }

    }
    public function getPaymentChannelDetail(Request $request)
    {
        $paymentMethodId = $request->id;
        $paymentChannels = PaymentChannel::find( $paymentMethodId);

        return response()->json([
            'name' => $paymentChannels->name,
            'code' => $paymentChannels->code,
            'type' => $paymentChannels->type,
            'min' => $paymentChannels->min,
            'max' => $paymentChannels->max,
            'action' => $paymentChannels->action,
            'image_url' => $paymentChannels->image_url,
        ]);
    }

    public function index()
    {
        return view('paymentmethod::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('paymentmethod::create');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('paymentmethod::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('paymentmethod::edit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
