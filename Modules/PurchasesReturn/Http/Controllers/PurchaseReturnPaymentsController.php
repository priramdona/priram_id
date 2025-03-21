<?php

namespace Modules\PurchasesReturn\Http\Controllers;

use Modules\PurchasesReturn\DataTables\PurchaseReturnPaymentsDataTable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Modules\PaymentMethod\Entities\PaymentMethod;
use Modules\PurchasesReturn\Entities\PurchaseReturn;
use Modules\PurchasesReturn\Entities\PurchaseReturnPayment;

class PurchaseReturnPaymentsController extends Controller
{

    public function index($purchase_return_id, PurchaseReturnPaymentsDataTable $dataTable) {
        abort_if(Gate::denies('access_purchase_return_payments'), 403);

        $purchase_return = PurchaseReturn::findOrFail($purchase_return_id);

        return $dataTable->render('purchasesreturn::payments.index', compact('purchase_return'));
    }


    public function create($purchase_return_id) {
        abort_if(Gate::denies('access_purchase_return_payments'), 403);

        $purchase_return = PurchaseReturn::findOrFail($purchase_return_id);

        return view('purchasesreturn::payments.create', compact('purchase_return'));
    }


    public function store(Request $request) {
        abort_if(Gate::denies('access_purchase_return_payments'), 403);

        $request->validate([
            'date' => 'required|date',
            'reference' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'note' => 'nullable|string|max:1000',
            'purchase_return_id' => 'required',
            'payment_method' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {

            $paymentMethodData = PaymentMethod::find($request->payment_method);

            PurchaseReturnPayment::create([
                'date' => $request->date,
                'reference' => $request->reference,
                'amount' => $request->amount,
                'note' => $request->note,
                'purchase_return_id' => $request->purchase_return_id,
                'payment_method_id' => $paymentMethodData->id ?? null,
                'payment_method' => $paymentMethodData->name ?? null,
                'payment_method_name' => $paymentMethodData->name ?? null,
                'business_id' => $request->user()->business_id,
            ]);

            $purchase_return = PurchaseReturn::findOrFail($request->purchase_return_id);

            $due_amount = $purchase_return->due_amount - $request->amount;

            if ($due_amount == $purchase_return->total_amount) {
                $payment_status = 'Unpaid';
            } elseif ($due_amount > 0) {
                $payment_status = 'Partial';
            } else {
                $payment_status = 'Paid';
            }

            $purchase_return->update([
                'paid_amount' => ($purchase_return->paid_amount + $request->amount),
                'due_amount' => $due_amount,
                'payment_status' => $payment_status
            ]);
        });

        toast(__('controller.created'), 'success');

        return redirect()->route('purchase-returns.index');
    }


    public function edit($purchase_return_id, PurchaseReturnPayment $purchaseReturnPayment) {
        abort_if(Gate::denies('access_purchase_return_payments'), 403);

        $purchase_return = PurchaseReturn::findOrFail($purchase_return_id);

        return view('purchasesreturn::payments.edit', compact('purchaseReturnPayment', 'purchase_return'));
    }


    public function update(Request $request, PurchaseReturnPayment $purchaseReturnPayment) {
        abort_if(Gate::denies('access_purchase_return_payments'), 403);

        $request->validate([
            'date' => 'required|date',
            'reference' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'note' => 'nullable|string|max:1000',
            'purchase_return_id' => 'required',
            'payment_method' => 'required|string|max:255'
        ]);

        DB::transaction(function () use ($request, $purchaseReturnPayment) {
            $purchase_return = $purchaseReturnPayment->purchaseReturn;

            $due_amount = ($purchase_return->due_amount + $purchaseReturnPayment->amount) - $request->amount;

            if ($due_amount == $purchase_return->total_amount) {
                $payment_status = 'Unpaid';
            } elseif ($due_amount > 0) {
                $payment_status = 'Partial';
            } else {
                $payment_status = 'Paid';
            }

            $purchase_return->update([
                'paid_amount' => (($purchase_return->paid_amount - $purchaseReturnPayment->amount) + $request->amount),
                'due_amount' => $due_amount,
                'payment_status' => $payment_status,

            ]);

            $paymentMethodData = PaymentMethod::find($request->payment_method);

            $purchaseReturnPayment->update([
                'date' => $request->date,
                'reference' => $request->reference,
                'amount' => $request->amount,
                'note' => $request->note,
                'purchase_return_id' => $request->purchase_return_id,
                'payment_method_id' => $paymentMethodData->id ?? null,
                'payment_method' => $paymentMethodData->name ?? null,
                'payment_method_name' => $paymentMethodData->name ?? null,
            ]);
        });

        toast(__('controller.updated'), 'info');

        return redirect()->route('purchase-returns.index');
    }


    public function destroy(PurchaseReturnPayment $purchaseReturnPayment) {
        abort_if(Gate::denies('access_purchase_return_payments'), 403);
        $purchaseReturn = $purchaseReturnPayment->purchaseReturn;

        $due_amount = $purchaseReturn->due_amount + $purchaseReturnPayment->amount;

        if ($due_amount == $purchaseReturn->total_amount) {
            $payment_status = 'Unpaid';
        } elseif ($due_amount > 0) {
            $payment_status = 'Partial';
        } else {
            $payment_status = 'Paid';
        }

        $purchaseReturn->update([
            'paid_amount' => ($purchaseReturn->paid_amount  - $purchaseReturnPayment->amount),
            'due_amount' => $due_amount,
            'payment_status' => $payment_status
        ]);


        $purchaseReturnPayment->delete();

        toast(__('controller.deleted'), 'warning');

        return redirect()->route('purchase-returns.index');
    }
}
