<?php

namespace Modules\Sale\Http\Controllers;

use Modules\Sale\DataTables\SalePaymentsDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Modules\PaymentMethod\Entities\PaymentMethod;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Entities\SalePayment;

class SalePaymentsController extends Controller
{

    public function index($sale_id, SalePaymentsDataTable $dataTable) {
        abort_if(Gate::denies('access_sale_payments'), 403);

        $sale = Sale::findOrFail($sale_id);

        return $dataTable->render('sale::payments.index', compact('sale'));
    }

    public function getPaymentSales(request $request) {

        $result = "1";
        $salePaymentData = SalePayment::where('sale_id',$request->sale_id)
        ->whereNotNull('payment_channel_id')
        ->get();

        if (blank($salePaymentData)){
            $result = "0";
        }

        return response()->json([
            'result' => $result
        ]);
    }

    public function create($sale_id) {
        abort_if(Gate::denies('access_sale_payments'), 403);

        $sale = Sale::findOrFail($sale_id);

        return view('sale::payments.create', compact('sale'));
    }


    public function store(Request $request) {
        $salePaymentInfo = SalePayment::where('sale_id',$request->sale_id)
        ->get();

        foreach( $salePaymentInfo as  $salePayment){
            if (!blank($salePayment->payment_channel_id)){
                toast(__('controller.update_error_online'), 'error');
                return back();
            }
        }

        abort_if(Gate::denies('access_sale_payments'), 403);

        $request->validate([
            'date' => 'required|date',
            'reference' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'note' => 'nullable|string|max:1000',
            'sale_id' => 'required',
            'payment_method' => 'required|string|max:255'
        ]);

        DB::transaction(function () use ($request) {

            $paymentMethodData = PaymentMethod::find($request->payment_method);

            SalePayment::create([
                'date' => $request->date,
                'reference' => $request->reference,
                'amount' => $request->amount,
                'note' => $request->note,
                'sale_id' => $request->sale_id,
                'payment_method_id' => $paymentMethodData->id ?? null,
                'payment_method' => $paymentMethodData->name ?? null,
                'payment_method_name' => $paymentMethodData->name ?? null,
                'business_id' => $request->user()->business_id,
            ]);

            $sale = Sale::findOrFail($request->sale_id);

            $due_amount = $sale->due_amount - $request->amount;

            if ($due_amount == $sale->total_amount) {
                $payment_status = 'Unpaid';
            } elseif ($due_amount > 0) {
                $payment_status = 'Partial';
            } else {
                $payment_status = 'Paid';
            }

            $sale->update([
                'paid_amount' => ($sale->paid_amount + $request->amount),
                'due_amount' => $due_amount,
                'payment_status' => $payment_status
            ]);
        });

        toast(__('controller.created'), 'success');

        return redirect()->route('sales.index');
    }


    public function edit($sale_id, SalePayment $salePayment) {
        if (!blank($salePayment->payment_channel_id)){
            toast(__('controller.error'), 'error');

            return redirect()->route('sales.index');
        }
        abort_if(Gate::denies('access_sale_payments'), 403);

        $sale = Sale::findOrFail($sale_id);

        return view('sale::payments.edit', compact('salePayment', 'sale'));
    }


    public function update(Request $request, SalePayment $salePayment) {
        if (!blank($salePayment->payment_channel_id)){
            toast(__('controller.error'), 'error');

            return redirect()->route('sales.index');
        }

        abort_if(Gate::denies('access_sale_payments'), 403);

        $request->validate([
            'date' => 'required|date',
            'reference' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'note' => 'nullable|string|max:1000',
            'sale_id' => 'required',
            'payment_method' => 'required|string|max:255'
        ]);


        DB::transaction(function () use ($request, $salePayment) {
            $sale = $salePayment->sale;

            $due_amount = ($sale->due_amount + $salePayment->amount) - $request->amount;

            if ($due_amount == $sale->total_amount) {
                $payment_status = 'Unpaid';
            } elseif ($due_amount > 0) {
                $payment_status = 'Partial';
            } else {
                $payment_status = 'Paid';
            }

            $sale->update([
                'paid_amount' => (($sale->paid_amount - $salePayment->amount) + $request->amount),
                'due_amount' => $due_amount,
                'payment_status' => $payment_status
            ]);

            $paymentMethodData = PaymentMethod::find($request->payment_method);

            $salePayment->update([
                'date' => $request->date,
                'reference' => $request->reference,
                'amount' => $request->amount,
                'note' => $request->note,
                'sale_id' => $request->sale_id,
                'payment_method_id' => $paymentMethodData->id ?? null,
                'payment_method' => $paymentMethodData->name ?? null,
                'payment_method_name' => $paymentMethodData->name ?? null,
            ]);
        });

        toast(__('controller.updated'), 'info');

        return redirect()->route('sales.index');
    }


    public function destroy(SalePayment $salePayment) {
        abort_if(Gate::denies('access_sale_payments'), 403);

        $sale = $salePayment->sale;

        $due_amount = $sale->due_amount + $salePayment->amount;

        if ($due_amount == $sale->total_amount) {
            $payment_status = 'Unpaid';
        } elseif ($due_amount > 0) {
            $payment_status = 'Partial';
        } else {
            $payment_status = 'Paid';
        }

        $sale->update([
            'paid_amount' => ($sale->paid_amount  - $salePayment->amount),
            'due_amount' => $due_amount,
            'payment_status' => $payment_status
        ]);

        $salePayment->delete();

        toast(__('controller.deleted'), 'warning');

        return redirect()->route('sales.index');
    }
}
