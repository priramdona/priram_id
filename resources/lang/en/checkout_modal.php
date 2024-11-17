<?php

return [
    'confirm_sale' => 'Confirm Sale',
    'confirm_selforder' => 'Confirm Self order',
    'received_amount' => 'Received Amount',
    'total_amount' => 'Total Amount',
    'payment_method' => 'Payment Method',
    'payment_channel' => 'Payment Channel',
    'select' => 'Select',
    'note' => 'Note',
    'summary' => [

        'total_products' => 'Total Products',
        'discount' => 'Discount',
        'total' => 'Total',
        'tax' => 'Tax',
        'shipping' => 'Shipping',
        'payment_fee' => 'Payment Fee',
        'ppn' => 'PPN',
        'ppn_from_payment' => '(From payment)',
        'application_fee' => 'Application Fee',
        'free' => 'Free',
        'grand_total' => 'Grand Total',
        'info' => 'Important, Process this payment mean You can not Cancel your cart.'
    ],
    'ovo' => [
        'phone_number' => 'OVO Number'
    ],
    'submit' => 'Submit',
    'proceed_payment' => 'Proceed Payment',
    'close' => 'Close',
    'action' => [
        'selforder' => [
            'info_customer' => 'Need Complete for this Payment Method',
            'phone' => 'Phone Number',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'gender' => 'Gender',
            'select' => 'Select',
            'male' => 'Male',
            'female' => 'Female',
            'dob' => 'Date of Birth',
            'email' => 'Email',
            'address' => 'Address',
            'city' => 'City',
            'province' => 'Province',
            'postal_code' => 'Postal Code',
            'button_close' => 'Close',
            'button_proceed' => 'Proceed',
        ],
    ],
    'payment_action' => [
        'account_no' => 'Number of Account',
        'account_name' => 'Name :',
        'account_expired' => 'Expired :',
        'email' => 'Email :',
        'whatsapp' => 'Whatsapp :',
        'payment_by' => 'Payment By',
        'footer_info' => 'Amount will automatically add to your Saldo after Settlement process',
        'footer_info_deducted' => 'Amount will Deducted from your Account',
        'button_check_payment' => 'Check Payment',
        'button_new_transaction' => 'New Transaction',
        'button_change_payment' => 'Change Payment',
    ],
    'continue_payment' => [
        'customer_selforder' => [
            'error' => [
                'title' => 'Error Information',
                'text' => 'Please Complete Information for this Payment Method',
            ]
        ],
        'ovo' => [
            'customer_error' => [
                'title' => 'Error Phone Number',
                'text' => 'Please Check Phone Number first!',
            ]
        ],
        'change_payment'=> [
            'title' => 'Change Payment',
            'text' => 'Are you want to change your Payment Method?',
            'button_confirm' => 'Confirm',
            'button_cancel' => 'Cancel',
        ],
        'payment_confirmation' => [
            'cancelled' => [
                'title' => 'Cancelled',
                'text' => 'Payment process has been cancelled',
            ],
            'error' => [
                'title' => 'Payment Error',
                'text' => 'Error process, please try again',
            ],
            'success' => [
                'title' => 'Payment Success',
                'text' => 'Your Payment has been Successful..!!',
            ],
            'title' => 'Payment Confirmation',
            'text' => 'Do you want to proceed this payment?',
            'button_confirm' => 'Confirm',
            'button_cancel' => 'Cancel',
            'confirmed' => [
                'title' => 'Important Payment Information',
                'html' => 'This payment is Online, Make sure to Complete the Payment Process',
                'button_proceed' => 'Proceed',
                'button_cancel' => 'Cancel',
                'proceed' => [
                    'title' => 'Being process',
                    'text' => 'Please wait your request is being processed',
                    'success' => [
                        'title' => 'Process success',
                        'text' => 'Your Payment has been generated',
                    ]
                    ],

            ]
        ],
        'payment_action' => [
            'qrcode' => [
                'lbl_payment_action' => 'Please Scan Barcode to Process payment',
            ],
            'account' => [
                'lbl_payment_action_1' => 'Please Transfer to ',
                'lbl_payment_action_2' => ' Virtual Account :',
            ],
            'info' => [
                'lbl_payment_action' => 'Please Check to Account : ',
            ],
            'url' => [
                'lbl_payment_action' => 'Please complete requirement below',
            ],
            'direct' => [
                'lbl_payment_action' => 'Please complete to Account : ',
            ],
            'links' => [
                'lbl_payment_action' => 'Please complete to Account : ',
                'input_payment_action_account' => 'Invoice has been Sent!',
                'input_payment_detail_label' => 'Please check Customer Email or Whatsapp',
            ]
        ],
        'paylater_invoice' => [
            'error' => [
                'title' => 'Payment Method Error',
                'text' => 'Must complete Customer for this Method',
            ],
            'confirmation' => [
                'title' => 'Confirmation Customer Information : ',
                'html_name' => ' Name  :',
                'html_phone' => ' Phone  :',
                'html_email' => ' Email  :',
                'html_info' => 'Please Check the Customer Information',
                'button_confirm' => 'Yes, Confirm!',
                'button_cancel' => 'No, cancel!',
            ]
        ],
        'new_transaction' => [
            'title' => 'Proceed New Transaction?',
            'text' => 'Payment Not Received, You can check Payment status on Sales transaction Lists',
            'button_confirm' => 'Confirm',
            'button_wait' => 'Wait',
        ],
        'waiting_payment' => [
            'title' => 'Awaiting Payment',
            'text' => 'Payment Not Received',
        ],
        'amount' => [
            'invalid_min' => [
                'title' => 'Amount Invalid',
                'text' => 'Minimum amount Invalid...',
            ],
            'invalid_max' => [
                'title' => 'Amount Invalid',
                'text' => 'Maksimum amount Invalid...',
            ],
        ]
    ]
];
