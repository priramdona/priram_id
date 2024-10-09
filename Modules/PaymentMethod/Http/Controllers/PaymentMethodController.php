<?php

namespace Modules\PaymentMethod\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\PaymentMethod\Entities\PaymentChannel;
use Modules\PaymentMethod\Entities\PaymentMethod;

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
    public function getPaymentChannels(Request $request)
    {
        $paymentMethodId = $request->payment_method;
        $paymentChannels = PaymentChannel::where('payment_method_id', $paymentMethodId)->get();

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
