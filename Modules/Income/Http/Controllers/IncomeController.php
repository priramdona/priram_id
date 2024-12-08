<?php

namespace Modules\Income\Http\Controllers;

use Modules\Income\DataTables\IncomesDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Income\Entities\Income;
use Modules\Income\Entities\IncomePayment;
use Modules\PaymentGateway\Entities\XenditCreatePayment;
use Modules\PaymentGateway\Entities\XenditPaymentMethod;
use Modules\PaymentGateway\Entities\xenditPaymentRequest;
use Modules\PaymentMethod\Entities\PaymentChannel;
use Modules\PaymentMethod\Entities\PaymentMethod;
use Modules\People\Entities\Customer;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Exp;

class IncomeController extends Controller
{

    public function index(IncomesDataTable $dataTable) {
        abort_if(Gate::denies('access_incomes'), 403);

        return $dataTable->render('income::incomes.index');
    }


    public function create() {
        abort_if(Gate::denies('create_incomes'), 403);
        $customers = Customer::where('business_id', Auth::user()->business_id)
        ->get();
        return view('income::incomes.create',compact('customers'));
    }


    public function store(Request $request) {
        abort_if(Gate::denies('create_incomes'), 403);

        $request->validate([
            'date' => 'required|date',
            'reference' => 'required|string|max:255',
            // 'category_id' => 'required',
            'customer_id' => 'required',
            'amount' => 'required|numeric|max:2147483647',
            'details' => 'nullable|string|max:1000'
        ]);

        $income = Income::create([
            'date' => $request->date,
            'customer_id' => $request->customer_id,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'paid_amount' => $request->grand_total,
            'additional_amount' => $request->grand_total - $request->amount,
            'payment_status' => 'waiting',
            'details' => $request->details,
            'business_id' => $request->user()->business_id,
        ]);

        if ($income->paid_amount > 0) {
            $paymentRequestData= null;
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
            $refIdData = $paymentRequestData['reference_id'] ?? null;

            IncomePayment::create([
                'date' => now()->format('Y-m-d'),
                'reference' => 'pay-inc/'.$income->reference,
                'reference_id' => $refIdData,
                'amount' => $income->amount,
                'income_id' => $income->id,
                'payment_method' => $paymentMethodData->name ?? null,
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
                $paymentMethod->transactional_id = $income->id;
                $paymentMethod->transactional_type = Income::class;
                $paymentMethod->save();
            }


            if ($paymentRequestData) {
                $paymentRequestData->source_type = Income::class;
                $paymentRequestData->source_id = $income->id;
                $paymentRequestData->save();
            }
        }

        toast(__('controller.created'), 'success');

        return redirect()->route('incomes.index');
    }


    public function edit(Income $income) {
        $paymentChannels = $income->incomePayments;
        if (!blank($paymentChannels->payment_channel_id)){

        toast(__('controller.income_error'), 'error');

        return redirect()->route('incomes.index');
        }

        abort_if(Gate::denies('edit_incomes'), 403);

        return view('income::incomes.edit', compact('income'));
    }


    public function update(Request $request, Income $income) {
        $paymentChannels = $income->incomePayments;
        if (!blank($paymentChannels->payment_channel_id)){

        toast(__('controller.income_error'), 'error');

        return redirect()->route('incomes.index');
        }
        abort_if(Gate::denies('edit_incomes'), 403);

        $request->validate([
            'date' => 'required|date',
            'reference' => 'required|string|max:255',
            // 'category_id' => 'required',
            'amount' => 'required|numeric|max:2147483647',
            'details' => 'nullable|string|max:1000'
        ]);

        $income->update([
            'date' => $request->date,
            'reference' => $request->reference,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'details' => $request->details
        ]);

        toast(__('controller.updated'), 'info');

        return redirect()->route('incomes.index');
    }


    public function destroy(Income $income) {
        $paymentChannels = $income->incomePayments;
        if (!blank($paymentChannels->payment_channel_id)){

        toast(__('controller.income_delete_error'), 'error');

        return redirect()->route('incomes.index');
        }
        abort_if(Gate::denies('delete_incomes'), 403);

        $income->delete();

        toast(__('controller.deleted'), 'warning');

        return redirect()->route('incomes.index');
    }
}
