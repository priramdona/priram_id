<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\businessAmount;
use Illuminate\Http\Request;
use Modules\PaymentGateway\Entities\XenditDisbursement;
use Modules\PaymentGateway\Http\Controllers\PaymentGatewayController;

use Illuminate\Support\Facades\Auth;
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
    public function history() {

        $history = businessAmount::query()
        ->where('business_id', Auth::user()->business_id)
        ->with('transactional')
        ->where('transactional_type', 'Modules\PaymentGateway\Entities\XenditDisbursement')
        ->orderBy('created_at', 'desc')
         ->get();

        return view('layouts.financial.history', [
            'history' => $history,
        ]);
    }
    public function detail($id) {

        $history = businessAmount::query()
        ->where('transactional_id', $id)
        ->with('transactional')
        ->first();
// dd($history->transactional);
        return view('layouts.financial.show', [
            'data' => $history,
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
            $request->notes ?? '',
        );
        // $businessAmount = Setting::firstOrFail();

        toast(__('controller.withdrawal_requested'), 'success');
        return redirect()->route('financial.management.withdraw');
    }
}
