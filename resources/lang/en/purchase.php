<?php

return [
    'breadcrumb' => [
        'home' => 'Home',
        'purchases' => 'Purchases'
    ],
    'show' => [
        'title' => 'Purchase Details',
        'details' => 'Details',
        'reference' => 'Reference',
        'print' => 'Print',
        'save' => 'Save',
        'company_info' => 'Company Info',
        'supplier_info' => 'Supplier Info',
        'invoice_info' => 'Invoice Info',
        'invoice' => 'Invoice',
        'date' => 'Date',
        'status' => 'Status',
        'payment_status' => 'Payment Status',
        'email' => 'Email',
        'phone' => 'Phone',
        'table' => [
            'product' => 'Product',
            'net_unit_price' => 'Net Unit Price',
            'quantity' => 'Quantity',
            'discount' => 'Discount',
            'tax' => 'Tax',
            'sub_total' => 'Sub Total'
        ],
        'discount' => 'Discount',
        'tax' => 'Tax',
        'shipping' => 'Shipping',
        'grand_total' => 'Grand Total'
    ],
    'print' => [
        'title' => 'Purchase Details',
        'reference' => 'Reference',
        'company_info' => 'Company Info',
        'supplier_info' => 'Supplier Info',
        'invoice_info' => 'Invoice Info',
        'invoice' => 'Invoice',
        'date' => 'Date',
        'status' => 'Status',
        'payment_status' => 'Payment Status',
        'email' => 'Email',
        'phone' => 'Phone',
        'table' => [
            'product' => 'Product',
            'net_unit_price' => 'Net Unit Price',
            'quantity' => 'Quantity',
            'discount' => 'Discount',
            'tax' => 'Tax',
            'sub_total' => 'Sub Total'
        ],
        'discount' => 'Discount',
        'tax' => 'Tax',
        'shipping' => 'Shipping',
        'grand_total' => 'Grand Total'
    ],
    'title' => 'Purchases',
    'index' => [
        'add_purchase' => 'Add Purchase'
    ],
    'edit' => [
        'title' => 'Edit Purchase',
        'supplier' => 'Supplier',
        'date' => 'Date',
        'reference' => 'Reference',
        'status' => 'Status',
        'payment_method' => 'Payment Method',
        'amount_received' => 'Amount Received',
        'note' => 'Note (If Needed)',
        'update_purchase' => 'Update Purchase',
        'status_options' => [
            'pending' => 'Pending',
            'ordered' => 'Ordered',
            'completed' => 'Completed'
        ]
    ],
    'create' => [
        'title' => 'Create Purchase',
        'supplier' => 'Supplier',
        'date' => 'Date',
        'reference' => 'Reference',
        'status' => 'Status',
        'payment_method' => 'Payment Method',
        'amount_paid' => 'Amount Paid',
        'note' => 'Note (If Needed)',
        'create_purchase' => 'Create Purchase',
        'select' => '-Select-',
        'status_options' => [
            'pending' => 'Pending',
            'ordered' => 'Ordered',
            'completed' => 'Completed'
        ]
    ],
    'payment' => [
        'create' => [
            'title' => 'Create Payment',
            'amount' => 'Amount',
            'payment_method' => 'Payment Method',
            'note' => 'Note (If Needed)',
            'create_payment' => 'Create Payment',
            'select' => '-Select-',
        ],
        'edit' => [
            'title' => 'Edit Payment',
            'amount' => 'Amount',
            'payment_method' => 'Payment Method',
            'note' => 'Note (If Needed)',
            'update_payment' => 'Update Payment',
        ],
        'index' => [
            'title' => 'Payment List',
            'add_payment' => 'Add Payment',
            'reference' => 'Reference',
            'amount' => 'Amount',
            'payment_method' => 'Payment Method',
            'date' => 'Date',
            'note' => 'Note',
            'status' => 'Status',
            'actions' => 'Actions',
        ],
    ],
'datatable' => [
    'purchase' => [
        'tools' => [
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
    'columns' => [
        'reference' => 'Reference',
                'date' => 'Date',
                'supplier' => 'Supplier',
                'status' => 'Status',
                'total_amount' => 'Total Amount',
                'paid_amount' => 'Paid Amount',
                'due_amount' => 'Due Amount',
                'payment_status' => 'Payment Status',
                'action' => 'Action',
    ],
    'buttons' => [
        'excel' => 'Excel',
        'print' => 'Print',
        'reset' => 'Reset',
        'reload' => 'Reload'
    ]
    ],
    'payment' => [
        'tools' => [
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
    'columns' => [
        'date' => 'Date',
        'reference' => 'Reference',
        'amount' => 'Amount',
        'payment_method' => 'Payment Method',
        'action' => 'Action',

    ],
    'buttons' => [
        'excel' => 'Excel',
        'print' => 'Print',
        'reset' => 'Reset',
        'reload' => 'Reload'
    ]
    ]

    ],
    'action' => [
        'show_payment' => 'Show Payments',
        'add_payment' => 'Add Payment',
        'edit' => 'Edit',
        'details' => 'Details',
        'delete' => 'Delete',
        'delete_confirmation' => 'Are you sure? It will delete the data permanently!',
    ]
];
