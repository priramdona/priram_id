<?php

return [
    'action' => 'Action',
    'create_income' => 'Create Income',
    'update_income' => 'Update Income',
    'reference' => 'Reference',
    'date' => 'Date',
    'category' => 'Category',
    'incomes_count' => 'Incomes Count',
    'customer' => 'Customer',
    'amount' => 'Amount',
    'details' => 'Details',
    'payment_method' => 'Payment Method',
    'payment_channel' => 'Payment Channel',
    'ovo_phone_number' => 'OVO Phone Number',
    'process_to_payment' => 'Process to Payment',
    'payment_fee' => 'Payment Fee',
    'ppn' => 'PPN',
    'application_fee' => 'Application Fee',
    'grand_total' => 'Grand Total',
    'select_category' => 'Select Category',
    'not_registered' => 'Not Registered',
    'select' => 'Select',
    'payment_by' => 'Payment by',
    'payment_information' => 'Amount will automatically add to your Saldo after settlement process',
    'new_transaction' => 'New Transaction',
    'home' => 'Home',
    'incomes' => 'Incomes',
    'add' => 'Add',
    'income_categories' => 'Income Categories',
    'add_category' => 'Add Category',
    'create_category' => 'Create Category',
    'category_name' => 'Category Name',
    'category_description' => 'Description',
    'create' => 'Create',
    'continue_payment' => [
        'customer' => [
            'error' => [
                'title' => 'Error Customer',
                'text' => 'Please select customer',
            ]
        ],
        'category' => [
            'error' => [
                'title' => 'Error Category',
                'text' => 'Please select category',
            ]
        ],
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
            'error' => [
                'title' => 'Error Payment Method',
                'text' => 'Please fill Amount first',
            ]
        ]
    ],
    'confirm_delete' => 'Are you sure? It will delete the data permanently!',
    'action_disabled_payment_online' => 'Action Disabled if Payment Online',
    'datatable' => [
        'sEmptyTable' => 'No record found',
        'sInfo' => 'Show _START_ to _END_ from _TOTAL_ entry',
        'sInfoEmpty' => 'Show 0 to 0 from 0 data',
        'sInfoFiltered' => '(filtered from _MAX_ all entry)',
        'sInfoPostFix' => '',
        'sInfoThousands' => '.',
        'sLengthMenu' => 'Show _MENU_ entry',
        'sLoadingRecords' => 'Loading...',
        'sProcessing' => 'Processing...',
        'sSearch' => 'Cari:',
        'sZeroRecords' => 'No Record match',
        'oPaginate' => [
            'sFirst' => 'First',
            'sLast' => 'Last',
            'sNext' => 'Next',
            'sPrevious' => 'Previous'
        ],
        'oAria' => [
            'sSortAscending' => ': enable to sort columns Ascending',
            'sSortDescending' => ': enable to sort columns descending'
        ]
        ],
];
