<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\PaymentGateway\Http\Controllers\PaymentGatewayController;

class FinacialController extends Controller
{
    public function index() {


        $paymentGateway =  new PaymentGatewayController();
        $balance = $paymentGateway->showBalance();

        // $businessAmount = Setting::firstOrFail();

        return view('layouts.financial.index', [
            'balance' => $balance,
        ]);
    }

    public function withdraw(Request $request) {

        if ($request->amount < 0 ){
            toast(__('controller.input_amount'), 'error');
            return redirect()->route('financial.management.withdraw');
        }
        $paymentGateway =  new PaymentGatewayController();
        $balance = $paymentGateway->showBalance();

        if ($request->amount > $balance ){
            toast(__('controller.insufficient_balance'), 'error');
            return redirect()->route('financial.management.withdraw');
        }

        $apiPaymentGateway = new PaymentGatewayController();

        $createDisbursement = $apiPaymentGateway->createDisbursement(
            $request->disbursement_method,
            $request->disbursement_channel,
            $request->account_name,
            $request->account_number,
            $request->amount,
            $request->transaction_amount,
            $request->notes,
        );
        // $businessAmount = Setting::firstOrFail();

        toast(__('controller.withdrawal_requested'), 'success');
        return redirect()->route('financial.management.withdraw');
    }
}
