<?php

return [
    'title' => 'Terms of Service',
    'intro' => 'Welcome to our application! By using this app, you agree to comply with the terms and conditions. Please read them carefully.',
    'features' => [
        'product_management' => 'Product Menu: For managing products with activities such as Add, Edit, Delete, and defining units, categorized accordingly.',
        'sales_menu' => 'Sales Menu: For transactions involving goods sold and payments received, with online payment media. This menu uses a camera as a barcode scanner.',
        'self_order_service' => [
            'intro' => 'Self-Order Service Menu: For transactions involving goods sold and payments received, directly processed by the customer. It consists of three types of services:',
            'mobile_order' => 'a. Mobile Order: The customer performs the transaction at the seller’s location, from taking the goods, managing the items, to choosing a payment method.',
            'stay_order' => 'b. Stay Order: The customer performs the transaction outside the seller’s location, managing the items that will be monitored by the admin until the order is fulfilled.',
            'delivery_order' => 'c. Delivery Order: This is a delivery service where the seller ships the goods to the customer, managing the delivery address and payment method.',
        ],
        'offer_menu' => 'Offer Menu: To send product offers with customized information. Online payment is available via Invoice.',
        'purchase_menu' => 'Purchase Menu: For transactions of incoming goods and outgoing payments, adding product capacity with supplier record.',
        'return_purchase' => 'Purchase Return Menu: Outgoing goods transaction for return documentation.',
        'return_sale' => 'Sale Return Menu: Incoming goods transaction for return documentation.',
        'expenses_menu' => 'Expenses Menu: For documenting financial outflows.',
        'income_menu' => 'Income Menu: For documenting financial inflows.',
        'stock_adjustment' => 'Stock Adjustment Menu: For adjusting stock quantities.',
        'parties_menu' => 'Parties Menu: Stores customer and supplier biodata.',
        'online_payment' => 'Online Payment Menu: Online payment media for transactions and payment-related information.',
        'report_menu' => 'Report Menu: Transaction recap reports such as profit and loss, payments, sales, purchases, sale returns, and purchase returns.',
        'balance_management' => 'Balance Management Menu: Media to withdraw virtual funds via bank or E-wallet transfers.',
        'user_management' => 'User Management Menu: Feature to manage additional users and their access rights.',
        'about_us' => 'About Us Menu: Contact facilities with options for Complaints, Feedback, and Questions.',
    ],
    'permissions' => [
        'camera' => 'Camera access is required to scan barcodes in the Cashier and Self-Order features.',
        'location' => 'Location access is required for accurate delivery in the Delivery Order feature.',
        'media' => 'Media access is required for images in products, profiles, and users.',
        'customer_info' => 'Customer information access is required for transaction needs and online payment media.',
        'supplier_info' => 'Supplier information access is needed for recording supplier-related transactions.',
        'user_info' => 'User information access is required for online payment transactions and communication with customers and suppliers.',
        'email_media' => 'Email media is used for invoice sending, contacting us, and other transactions.',
    ],
    'acceptance' => 'By continuing to use this application, you agree to all the terms and conditions outlined.',
];
