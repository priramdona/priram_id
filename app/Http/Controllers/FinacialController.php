<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\PaymentGateway\Http\Controllers\PaymentGatewayController;

class FinacialController extends Controller
{
    public function index() {


        // $businessAmount = Setting::firstOrFail();

        return view('layouts.financial.index');
    }

    public function withdraw(Request $request) {

        $apiPaymentGateway = new PaymentGatewayController();

        $createDisbursement = $apiPaymentGateway->createDisbursement(
            $request->disbursement_method,
            $request->disbursement_channel,
            $request->account_name,
            $request->account_number,
            $request->amount,
            $request->notes,
        );


        dd($createDisbursement);
        // $businessAmount = Setting::firstOrFail();

        return redirect()->route('settings.index');
    }
}
