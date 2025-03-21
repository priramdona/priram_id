<?php

namespace Modules\SalesReturn\Http\Controllers;

use Modules\SalesReturn\DataTables\SaleReturnPaymentsDataTable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Modules\PaymentMethod\Entities\PaymentMethod;
use Modules\SalesReturn\Entities\SaleReturn;
use Modules\SalesReturn\Entities\SaleReturnPayment;

class SaleReturnPaymentsController extends Controller
{

    public function index($sale_return_id, SaleReturnPaymentsDataTable $dataTable) {
        abort_if(Gate::denies('access_sale_return_payments'), 403);

        $sale_return = SaleReturn::findOrFail($sale_return_id);

        return $dataTable->render('salesreturn::payments.index', compact('sale_return'));
    }


    public function create($sale_return_id) {
        abort_if(Gate::denies('access_sale_return_payments'), 403);

        $sale_return = SaleReturn::findOrFail($sale_return_id);

        return view('salesreturn::payments.create', compact('sale_return'));
    }


    public function store(Request $request) {
        abort_if(Gate::denies('access_sale_return_payments'), 403);

        $request->validate([
            'date' => 'required|date',
            'reference' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'note' => 'nullable|string|max:1000',
            'sale_return_id' => 'required',
            'payment_method' => 'required|string|max:255'
        ]);

        DB::transaction(function () use ($request) {
            $paymentMethodData = PaymentMethod::find($request->payment_method);

            SaleReturnPayment::create([
                'date' => $request->date,
                'reference' => $request->reference,
                'amount' => $request->amount,
                'note' => $request->note,
                'sale_return_id' => $request->sale_return_id,
                'payment_method_id' => $paymentMethodData->id ?? null,
                'payment_method' => $paymentMethodData->name ?? null,
                'payment_method_name' => $paymentMethodData->name ?? null,
                'business_id' => $request->user()->business_id,
            ]);

            $sale_return = SaleReturn::findOrFail($request->sale_return_id);

            $due_amount = $sale_return->due_amount - $request->amount;

            if ($due_amount == $sale_return->total_amount) {
                $payment_status = 'Unpaid';
            } elseif ($due_amount > 0) {
                $payment_status = 'Partial';
            } else {
                $payment_status = 'Paid';
            }

            $sale_return->update([
                'paid_amount' => ($sale_return->paid_amount + $request->amount),
                'due_amount' => $due_amount,
                'payment_status' => $payment_status
            ]);
        });

        toast(__('controller.created'), 'success');

        return redirect()->route('sale-returns.index');
    }


    public function edit($sale_return_id, SaleReturnPayment $saleReturnPayment) {
        abort_if(Gate::denies('access_sale_return_payments'), 403);

        $sale_return = SaleReturn::findOrFail($sale_return_id);

        return view('salesreturn::payments.edit', compact('saleReturnPayment', 'sale_return'));
    }


    public function update(Request $request, SaleReturnPayment $saleReturnPayment) {
        abort_if(Gate::denies('access_sale_return_payments'), 403);

        $request->validate([
            'date' => 'required|date',
            'reference' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'note' => 'nullable|string|max:1000',
            'sale_return_id' => 'required',
            'payment_method' => 'required|string|max:255'
        ]);

        DB::transaction(function () use ($request, $saleReturnPayment) {
            $sale_return = $saleReturnPayment->saleReturn;

            $due_amount = ($sale_return->due_amount + $saleReturnPayment->amount) - $request->amount;

            if ($due_amount == $sale_return->total_amount) {
                $payment_status = 'Unpaid';
            } elseif ($due_amount > 0) {
                $payment_status = 'Partial';
            } else {
                $payment_status = 'Paid';
            }

            $sale_return->update([
                'paid_amount' => (($sale_return->paid_amount - $saleReturnPayment->amount) + $request->amount),
                'due_amount' => $due_amount,
                'payment_status' => $payment_status
            ]);

            $paymentMethodData = PaymentMethod::find($request->payment_method);

            $saleReturnPayment->update([
                'date' => $request->date,
                'reference' => $request->reference,
                'amount' => $request->amount,
                'note' => $request->note,
                'sale_return_id' => $request->sale_return_id,
                'payment_method_id' => $paymentMethodData->id ?? null,
                'payment_method' => $paymentMethodData->name ?? null,
                'payment_method_name' => $paymentMethodData->name ?? null,
            ]);
        });

        toast(__('controller.updated'), 'info');

        return redirect()->route('sale-returns.index');
    }


    public function destroy(SaleReturnPayment $saleReturnPayment) {
        abort_if(Gate::denies('access_sale_return_payments'), 403);

        $saleReturn = $saleReturnPayment->saleReturn;

        $due_amount = $saleReturn->due_amount + $saleReturnPayment->amount;

        if ($due_amount == $saleReturn->total_amount) {
            $payment_status = 'Unpaid';
        } elseif ($due_amount > 0) {
            $payment_status = 'Partial';
        } else {
            $payment_status = 'Paid';
        }

        $saleReturn->update([
            'paid_amount' => ($saleReturn->paid_amount  - $saleReturnPayment->amount),
            'due_amount' => $due_amount,
            'payment_status' => $payment_status
        ]);

        $saleReturnPayment->delete();

        toast(__('controller.deleted'), 'warning');

        return redirect()->route('sale-returns.index');
    }
}
