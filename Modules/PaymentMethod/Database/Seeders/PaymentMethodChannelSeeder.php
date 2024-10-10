<?php

namespace Modules\PaymentMethod\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PaymentMethod\Entities\PaymentChannel;
use Modules\PaymentMethod\Entities\PaymentMethod;

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
        }
}
public function virtualAccount($idPaymentMethod): array
    {
        $dataEWallet = [
            [
                'name' => 'Bank BNI',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'BNI',
                'type' => 'VIRTUAL_ACCOUNT',
                'reference' => null,
                'status' => true,
                'image' => 'VA/BNI.png',
                'action' => 'account',
                'min' => 10000,
                'max' => 10000000,

                'fee_type_1' => 'amount',
                'fee_value_1' => 4000,
                'fee_type_2' => null,
                'fee_value_2' => null,
            ],
            [
                'name' => 'Bank BRI',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'BRI',
                'type' => 'VIRTUAL_ACCOUNT',
                'reference' => null,
                'status' => true,
                'image' => 'VA/BRI.png',
                'action' => 'account',
                'min' => 10000,
                'max' => 10000000,
                'fee_type_1' => 'amount',
                'fee_value_1' => 4000,
                'fee_type_2' => null,
                'fee_value_2' => null,
            ],
            [
                'name' => 'Bank Mandiri',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'MANDIRI',
                'type' => 'VIRTUAL_ACCOUNT',
                'reference' => null,
                'status' => true,
                'image' => 'VA/MANDIRI.png',
                'action' => 'account',
                'min' => 10000,
                'max' => 10000000,
                'fee_type_1' => 'amount',
                'fee_value_1' => 4000,
                'fee_type_2' => null,
                'fee_value_2' => null,
            ],
            [
                'name' => 'Cimb Niaga',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'CIMB',
                'type' => 'VIRTUAL_ACCOUNT',
                'reference' => null,
                'status' => true,
                'image' => 'VA/CIMB.png',
                'action' => 'account',
                'min' => 10000,
                'max' => 10000000,
                'fee_type_1' => 'amount',
                'fee_value_1' => 4000,
                'fee_type_2' => null,
                'fee_value_2' => null,
            ],
            [
                'name' => 'Bank BSI',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'BSI',
                'type' => 'VIRTUAL_ACCOUNT',
                'reference' => null,
                'status' => true,
                'image' => 'VA/BSI.png',
                'action' => 'account',
                'min' => 10000,
                'max' => 10000000,
                'fee_type_1' => 'amount',
                'fee_value_1' => 4000,
                'fee_type_2' => null,
                'fee_value_2' => null,
            ],
            [
                'name' => 'Bank Permata',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'PERMATA',
                'type' => 'VIRTUAL_ACCOUNT',
                'reference' => null,
                'status' => true,
                'image' => 'VA/PERMATA.png',
                'action' => 'account',
                'min' => 10000,
                'max' => 10000000,
                'fee_type_1' => 'amount',
                'fee_value_1' => 4000,
                'fee_type_2' => null,
                'fee_value_2' => null,
            ],
            [
                'name' => 'Bank Sampoerna',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'BSS',
                'type' => 'VIRTUAL_ACCOUNT',
                'reference' => null,
                'status' => true,
                'image' => 'VA/BSS.png',
                'action' => 'account',
                'min' => 10000,
                'max' => 10000000,
                'fee_type_1' => 'amount',
                'fee_value_1' => 4000,
                'fee_type_2' => null,
                'fee_value_2' => null,
            ],
            [
                'name' => 'Bank BJB',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'BJB',
                'type' => 'VIRTUAL_ACCOUNT',
                'reference' => null,
                'status' => true,
                'image' => 'VA/BJB.png',
                'action' => 'account',
                'min' => 10000,
                'max' => 10000000,
                'fee_type_1' => 'amount',
                'fee_value_1' => 4000,
                'fee_type_2' => null,
                'fee_value_2' => null,
            ]

    ];

    return $dataEWallet;
    }
public function eWallet($idPaymentMethod): array
    {
        $dataEWallet = [
            [
                'name' => 'OVO',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'ID_OVO',
                'type' => 'EWALLET',
                'reference' => null,
                'status' => true,
                'image' => 'ewallet/logo-ovo.png',
                'action' => 'qrcode',
                'min' => 10000,
                'max' => 10000000,
                'fee_type_1' => '%',
                'fee_value_1' => 1.5,
                'fee_type_2' => '%',
                'fee_value_2' => '11',

            ],
            [
                'name' => 'DANA',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'ID_DANA',
                'type' => 'EWALLET',
                'reference' => null,
                'status' => true,
                'image' => 'ewallet/logo-dana.png',
                'action' => 'qrcode',
                'min' => 10000,
                'max' => 10000000,
                'fee_type_1' => '%',
                'fee_value_1' => 1.5,
                'fee_type_2' => null,
                'fee_value_2' => null,
            ],
            [
                'name' => 'LinkAja',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'ID_LINKAJA',
                'type' => 'EWALLET',
                'reference' => null,
                'status' => true,
                'image' => 'ewallet/logo-linkaja.png',
                'action' => 'qrcode',
                'min' => 10000,
                'max' => 10000000,
                'fee_type_1' => '%',
                'fee_value_1' => 1.5,
                'fee_type_2' => null,
                'fee_value_2' => null,
            ],
            [
                'name' => 'Shopeepay',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'ID_SHOPEEPAY',
                'type' => 'EWALLET',
                'reference' => null,
                'status' => true,
                'image' => 'ewallet/logo-shopeepay.png',
                'action' => 'qrcode',
                'min' => 10000,
                'max' => 10000000,
                'fee_type_1' => '%',
                'fee_value_1' => 1.8018,
                'fee_type_2' => null,
                'fee_value_2' => null,
            ],
            [
                'name' => 'AstraPay',
                'payment_method_id' => $idPaymentMethod,
                'code' => 'ID_ASTRAPAY',
                'type' => 'EWALLET',
                'reference' => null,
                'status' => true,
                'image' => 'ewallet/logo-astrapay.png',
                'action' => 'qrcode',
                'min' => 10000,
                'max' => 10000000,
                'fee_type_1' => '%',
                'fee_value_1' => 1.5,
                'fee_type_2' => null,
                'fee_value_2' => null,
            ]

    ];

    return $dataEWallet;
    }
}
