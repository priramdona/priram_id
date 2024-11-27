<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Income\Entities\IncomePayment;
use Modules\PaymentMethod\Entities\PaymentMethod;
use Modules\People\Entities\Customer;
use Modules\People\Entities\Supplier;
use Modules\Purchase\Entities\PurchasePayment;
use Modules\PurchasesReturn\Entities\PurchaseReturnPayment;
use Modules\Sale\Entities\SalePayment;
use Modules\Sale\Entities\SelforderCheckoutPayment;
use Modules\SalesReturn\Entities\SaleReturnPayment;

use Illuminate\Support\Facades\Auth;
class PaymentsReport extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $start_date;
    public $end_date;
    public $payments;
    public $payment_method;
    public $paymentMethods = []; // Array untuk menyimpan opsi payment method

    protected $rules = [
        'start_date' => 'required|date|before:end_date',
        'end_date'   => 'required|date|after:start_date',
        'payments'   => 'required|string'
    ];
    protected $query;

    public function mount() {
        $this->start_date = today()->subDays(30)->format('Y-m-d');
        $this->end_date = today()->format('Y-m-d');
        $this->payments = '';
        $this->query = null;
    }

    public function loadPaymentMethods($paymentType)
    {

        if ($paymentType == 'income'){
            $paymentMethod = PaymentMethod::where('status',true)
            ->where('is_income',true)->get();
        }

        if ($paymentType == 'selforder'){
            $paymentMethod = PaymentMethod::where('status',true)
            ->where('is_selforder',true)->get();
        }

        if ($paymentType == 'sale'){
            $paymentMethod = PaymentMethod::where('status',true)
            ->where('is_sale',true)->get();
        }

        if ($paymentType == 'pos'){
            $paymentMethod = PaymentMethod::where('status',true)
            ->where('is_pos',true)->get();
        }

        if ($paymentType == 'purchase'){
            $paymentMethod = PaymentMethod::where('status',true)
            ->where('is_purchase',true)->get();
        }


        if ($paymentType == 'purchase_return'){
            $paymentMethod = PaymentMethod::where('status',true)
            ->where('is_purchase_return',true)->get();
        }

        if ($paymentType == 'sale_return'){
            $paymentMethod = PaymentMethod::where('status',true)
            ->where('is_sale_return',true)->get();
        }

        if ($paymentType == 'quotation'){
            $paymentMethod = PaymentMethod::where('status',true)
            ->where('is_quotation',true)->get();
        }


        // Assign opsi payment method berdasarkan pilihan payments
        $this->paymentMethods = $paymentMethod ?? [];
    }

    public function render() {
        $this->getQuery();

        return view('livewire.reports.payments-report', [
            'information' => $this->query ? $this->query->orderBy('date', 'desc')
                ->when($this->start_date, function ($query) {
                    return $query->whereDate('date', '>=', $this->start_date);
                })
                ->when($this->end_date, function ($query) {
                    return $query->whereDate('date', '<=', $this->end_date);
                })
                ->when($this->payment_method, function ($query) {
                    return $query->where('payment_method', $this->payment_method);
                })
                ->paginate(10) : collect()
        ]);
    }

    public function generateReport() {
        $this->validate();
        $this->render();
    }

    public function updatedPayments($value) {
        $this->resetPage();
        $this->loadPaymentMethods($value);
    }

    public function getQuery() {
        if ($this->payments == 'sale' || $this->payments == 'pos') {
            $this->query = SalePayment::query()->with('sale')
            ->where('business_id', Auth::user()->business_id)->where('business_id', Auth::user()->business_id);
        } elseif ($this->payments == 'sale_return') {
            $this->query = SaleReturnPayment::query()->with('saleReturn')
            ->where('business_id', Auth::user()->business_id);
        } elseif ($this->payments == 'income') {
            $this->query = IncomePayment::query()->with('income')
            ->where('business_id', Auth::user()->business_id);
        }elseif ($this->payments == 'selforder') {
                $this->query = SelforderCheckoutPayment::query()->with('selforderCheckout')
                ->where('business_id', Auth::user()->business_id);
        } elseif ($this->payments == 'purchase') {
            $this->query = PurchasePayment::query()->with('purchase')
            ->where('business_id', Auth::user()->business_id);
        } elseif ($this->payments == 'purchase_return') {
            $this->query = PurchaseReturnPayment::query()->with('purchaseReturn')
            ->where('business_id', Auth::user()->business_id);
        } else {
            $this->query = null;
        }
    }
}
