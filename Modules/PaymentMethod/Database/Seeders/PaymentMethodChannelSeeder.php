<?php

namespace Modules\PaymentMethod\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PaymentMethod\Entities\PaymentChannel;
use Modules\PaymentMethod\Entities\PaymentMethod;
use PHPUnit\Framework\Constraint\IsFalse;

class PaymentMethodChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataPaymentMethods = [
                [
                    'name' => 'Cash',
                    'code' => 'CASH',
                    'type' => 'CASH',
                    'reference' => null,
                    'status' => true,
                ],
                [
                    'name' => 'Virtual Account (VA)',
                    'code' => 'VIRTUAL_ACCOUNT',
                    'type' => 'VIRTUAL_ACCOUNT',
                    'reference' => null,
                    'status' => true,
                ],
                [
                    'name' => 'E-Wallet',
                    'code' => 'EWALLET',
                    'type' => 'EWALLET',
                    'reference' => null,
                    'status' => true,
                ],
                [
                    'name' => 'Paylater',
                    'code' => 'PAYLATER',
                    'type' => 'PAYLATER',
                    'reference' => null,
                    'status' => false,
                ],
                [
                    'name' => 'QRIS',
                    'code' => 'QR_CODE',
                    'type' => 'QR_CODE',
                    'reference' => null,
                    'status' => true,
                ],
                [
                    'name' => 'Debit',
                    'code' => 'DIRECT_DEBIT',
                    'type' => 'DIRECT_DEBIT',
                    'reference' => null,
                    'status' => false,
                ],
                [
                    'name' => 'Counter',
                    'code' => 'OVER_THE_COUNTER',
                    'type' => 'OVER_THE_COUNTER',
                    'reference' => null,
                    'status' => false,
                ],
                [
                    'name' => 'Credit Card',
                    'code' => 'CARD',
                    'type' => 'CARD',
                    'reference' => null,
                    'status' => false,
                ]
           ];


           foreach($dataPaymentMethods as $paymentMethod) {

            $paymentMethodData = PaymentMethod::create($paymentMethod);
            if ($paymentMethod['code'] == 'EWALLET') {

                $dataEwallets = $this->ewallet($paymentMethodData->id);

                foreach($dataEwallets as $dataEwallet){
                    PaymentChannel::create($dataEwallet);
                };

            }
            if ($paymentMethod['code'] == 'VIRTUAL_ACCOUNT') {

                $VA = $this->virtualAccount($paymentMethodData->id);

                foreach($VA as $dataVA){
                    PaymentChannel::create($dataVA);
                };

            }
            if ($paymentMethod['code'] == 'QR_CODE') {

                $qrisData = $this->qrisData($paymentMethodData->id);

                foreach($qrisData as $qris){
                    PaymentChannel::create($qris);
                };
            }
        }
}
public function virtualAccount($idPaymentMethod): array
    {
        $dataEWallet = [
            [
                'name' => 'Bank BCA',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'BCA',
                'type' => 'VIRTUAL_ACCOUNT',
                'reference' => null,
                'status' => false,
                'source' => 'xendit',
                'image' => 'VA/BNI.png',
                'action' => 'account',
                'min' => 10000,
                'max' => 50000000,
                'fee_type_1' => 'amount',
                'fee_value_1' => 4500,
                'fee_type_2' => null,
                'fee_value_2' => null,
                'is_ppn' => true,
                'expired' => 15,
                'payment_process' => 'instant',
                'settlement' => 1,
            ],
            [
                'name' => 'Bank BNI',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'BNI',
                'type' => 'VIRTUAL_ACCOUNT',
                'reference' => null,
                'status' => true,
                'source' => 'xendit',
                'image' => 'VA/BNI.png',
                'action' => 'account',
                'min' => 10000,
                'max' => 5000000000,
                'fee_type_1' => 'amount',
                'fee_value_1' => 4500,
                'fee_type_2' => null,
                'fee_value_2' => null,
                'is_ppn' => true,
                'expired' => 15,
                'payment_process' => 'instant',
                'settlement' => 0,
            ],
            [
                'name' => 'Bank BRI',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'BRI',
                'type' => 'VIRTUAL_ACCOUNT',
                'reference' => null,
                'status' => true,
                'source' => 'xendit',
                'image' => 'VA/BRI.png',
                'action' => 'account',
                'min' => 10000,
                'max' => 10000000,
                'fee_type_1' => 'amount',
                'fee_value_1' => 4500,
                'fee_type_2' => null,
                'fee_value_2' => null,
                'is_ppn' => true,
                'expired' => 15,
                'payment_process' => 'instant',
                'settlement' => 0,
            ],
            [
                'name' => 'Bank Mandiri',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'MANDIRI',
                'type' => 'VIRTUAL_ACCOUNT',
                'reference' => null,
                'status' => true,
                'source' => 'xendit',
                'image' => 'VA/MANDIRI.png',
                'action' => 'account',
                'min' => 10000,
                'max' => 5000000000,
                'fee_type_1' => 'amount',
                'fee_value_1' => 5000,
                'fee_type_2' => null,
                'fee_value_2' => null,
                'is_ppn' => true,
                'expired' => 15,
                'payment_process' => 'instant',
                'settlement' => 0,
            ],
            [
                'name' => 'Cimb Niaga',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'CIMB',
                'type' => 'VIRTUAL_ACCOUNT',
                'reference' => null,
                'status' => true,
                'source' => 'xendit',
                'image' => 'VA/CIMB.png',
                'action' => 'account',
                'min' => 10000,
                'max' => 50000000,
                'fee_type_1' => 'amount',
                'fee_value_1' => 4500,
                'fee_type_2' => null,
                'fee_value_2' => null,
                'is_ppn' => true,
                'expired' => 15,
                'payment_process' => 'instant',
                'settlement' => 0,
            ],
            [
                'name' => 'Bank BSI',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'BSI',
                'type' => 'VIRTUAL_ACCOUNT',
                'reference' => null,
                'status' => true,
                'source' => 'xendit',
                'image' => 'VA/BSI.png',
                'action' => 'account',
                'min' => 10000,
                'max' => 5000000000,
                'fee_type_1' => 'amount',
                'fee_value_1' => 4500,
                'fee_type_2' => null,
                'fee_value_2' => null,
                'is_ppn' => true,
                'expired' => 15,
                'payment_process' => 'instant',
                'settlement' => 0,
            ],
            [
                'name' => 'Bank Permata',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'PERMATA',
                'type' => 'VIRTUAL_ACCOUNT',
                'reference' => null,
                'status' => true,
                'source' => 'xendit',
                'image' => 'VA/PERMATA.png',
                'action' => 'account',
                'min' => 10000,
                'max' => 999999999,
                'fee_type_1' => 'amount',
                'fee_value_1' => 4500,
                'fee_type_2' => null,
                'fee_value_2' => null,
                'is_ppn' => true,
                'expired' => 15,
                'payment_process' => 'instant',
                'settlement' => 0,
            ],
            [
                'name' => 'Bank Sampoerna',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'BSS',
                'type' => 'VIRTUAL_ACCOUNT',
                'reference' => null,
                'status' => false,
                'source' => 'xendit',
                'image' => 'VA/BSS.png',
                'action' => 'account',
                'min' => 10000,
                'max' => 5000000000,
                'fee_type_1' => 'amount',
                'fee_value_1' => 4500,
                'fee_type_2' => null,
                'fee_value_2' => null,
                'is_ppn' => true,
                'expired' => 15,
                'payment_process' => 'instant',
                'settlement' => 0,
            ],
            [
                'name' => 'Bank BJB',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'BJB',
                'type' => 'VIRTUAL_ACCOUNT',
                'reference' => null,
                'status' => true,
                'source' => 'xendit',
                'image' => 'VA/BJB.png',
                'action' => 'account',
                'min' => 10000,
                'max' => 500000000,
                'fee_type_1' => 'amount',
                'fee_value_1' => 4500,
                'fee_type_2' => null,
                'fee_value_2' => null,
                'is_ppn' => true,
                'expired' => 15,
                'payment_process' => 'instant',
                'settlement' => 0,
            ]

    ];

    return $dataEWallet;
    }
    public function qrisData($idPaymentMethod): array
    {
        $dataQRIS = [
            [
                'name' => 'QRIS',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'QRIS',
                'type' => 'QR_CODE',
                'reference' => null,
                'status' => true,
                'source' => 'xendit',
                'image' => 'qris/qris-logo.png',
                'action' => 'qrcode',
                'min' => 10000,
                'max' => 10000000,
                'fee_type_1' => '%',
                'fee_value_1' => 1,
                'fee_type_2' => null,
                'fee_value_2' => null,
                'is_ppn' => false,
                'expired' => 2880,
                'payment_process' => 'instant',
                'settlement' => 2,
            ]

    ];
        return $dataQRIS;
    }
public function eWallet($idPaymentMethod): array
    {
        $dataEWallet = [
            [
                'name' => 'OVO',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'OVO',
                'type' => 'EWALLET',
                'reference' => null,
                'status' => true,
                'source' => 'xendit',
                'image' => 'ewallet/logo-ovo.png',
                'action' => 'qrcode',
                'min' => 10000,
                'max' => 2000000,
                'fee_type_1' => '%',
                'fee_value_1' => 2,
                'fee_type_2' => null,
                'fee_value_2' => null,
                'is_ppn' => true,
                'expired' => 1,
                'payment_process' => 'instant',
                'settlement' => 2,

            ],
            [
                'name' => 'DANA',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'DANA',
                'type' => 'EWALLET',
                'reference' => null,
                'status' => true,
                'source' => 'xendit',
                'image' => 'ewallet/logo-dana.png',
                'action' => 'qrcode',
                'min' => 10000,
                'max' => 2000000,
                'fee_type_1' => '%',
                'fee_value_1' => 2,
                'fee_type_2' => null,
                'fee_value_2' => null,
                'is_ppn' => true,
                'expired' => 5,
                'payment_process' => 'instant',
                'settlement' => 2,
            ],
            [
                'name' => 'LinkAja',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'LINKAJA',
                'type' => 'EWALLET',
                'reference' => null,
                'status' => true,
                'source' => 'xendit',
                'image' => 'ewallet/logo-linkaja.png',
                'action' => 'qrcode',
                'min' => 10000,
                'max' => 2000000,
                'fee_type_1' => '%',
                'fee_value_1' => 2,
                'fee_type_2' => null,
                'fee_value_2' => null,
                'is_ppn' => true,
                'expired' => 2880,
                'payment_process' => 'instant',
                'settlement' => 2,
            ],
            [
                'name' => 'Shopeepay',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'SHOPEEPAY',
                'type' => 'EWALLET',
                'reference' => null,
                'status' => true,
                'source' => 'xendit',
                'image' => 'ewallet/logo-shopeepay.png',
                'action' => 'qrcode',
                'min' => 10000,
                'max' => 2000000,
                'fee_type_1' => '%',
                'fee_value_1' => 2.5,
                'fee_type_2' => null,
                'fee_value_2' => null,
                'is_ppn' => true,
                'expired' => 30,
                'payment_process' => 'instant',
                'settlement' => 2,
            ],
            [
                'name' => 'AstraPay',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'ASTRAPAY',
                'type' => 'EWALLET',
                'reference' => null,
                'status' => true,
                'source' => 'xendit',
                'image' => 'ewallet/logo-astrapay.png',
                'action' => 'qrcode',
                'min' => 10000,
                'max' => 2000000,
                'fee_type_1' => '%',
                'fee_value_1' => 2,
                'fee_type_2' => null,
                'fee_value_2' => null,
                'is_ppn' => true,
                'expired' => 15,
                'payment_process' => 'instant',
                'settlement' => 2,
            ]

    ];

    return $dataEWallet;
    }
}
