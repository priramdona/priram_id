<?php

namespace App\Livewire\Pos\includes;
use Livewire\Component;
class checkoutModal extends Component
{
    public $lblPaymentHidden = true;
    public $paymentAccountText = '123456789';
    public function showLabel()
    {
        $this->lblPaymentHidden = false;
    }

    public function changeText()
    {
        $this->paymentAccountText = 'Silahkan ketik 123456789';
    }

    public function render()
    {
        return view('livewire.pos.includes.checkout-modal');
    }
}
