<?php

namespace App\Livewire\Pos\Includes;

use Livewire\Component;

class CheckoutPayment extends Component
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
        $this->emit('keepModalOpen');
    }

    public function render()
    {
        return view('livewire.pos.includes.checkout-payment');
    }
}
