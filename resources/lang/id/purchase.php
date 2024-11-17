<?php

return [
    'breadcrumb' => [
        'home' => 'Beranda',
        'purchases' => 'Pembelian'
    ],
    'show' => [
        'title' => 'Detail Pembelian',
        'details' => 'Detail',
        'reference' => 'Referensi',
        'print' => 'Cetak',
        'save' => 'Simpan',
        'company_info' => 'Info Usaha',
        'supplier_info' => 'Info Pemasok',
        'invoice_info' => 'Info Faktur',
        'invoice' => 'Faktur',
        'date' => 'Tanggal',
        'status' => 'Status',
        'payment_status' => 'Status Pembayaran',
        'email' => 'Email',
        'phone' => 'Telepon',
        'table' => [
            'product' => 'Produk',
            'net_unit_price' => 'Harga Satuan Bersih',
            'quantity' => 'Jumlah',
            'discount' => 'Diskon',
            'tax' => 'Pajak',
            'sub_total' => 'Sub Total'
        ],
        'discount' => 'Diskon',
        'tax' => 'Pajak',
        'shipping' => 'Pengiriman',
        'grand_total' => 'Total Keseluruhan'
    ],
    'print' => [
        'title' => 'Detail Pembelian',
        'reference' => 'Referensi',
        'company_info' => 'Info Usaha',
        'supplier_info' => 'Info Pemasok',
        'invoice_info' => 'Info Faktur',
        'invoice' => 'Faktur',
        'date' => 'Tanggal',
        'status' => 'Status',
        'payment_status' => 'Status Pembayaran',
        'email' => 'Email',
        'phone' => 'Telepon',
        'table' => [
            'product' => 'Produk',
            'net_unit_price' => 'Harga Satuan Bersih',
            'quantity' => 'Jumlah',
            'discount' => 'Diskon',
            'tax' => 'Pajak',
            'sub_total' => 'Sub Total'
        ],
        'discount' => 'Diskon',
        'tax' => 'Pajak',
        'shipping' => 'Pengiriman',
        'grand_total' => 'Total Keseluruhan'
    ],
    'title' => 'Pembelian',
    'index' => [
        'add_purchase' => 'Tambah Pembelian'
    ],
    'edit' => [
        'title' => 'Edit Pembelian',
        'supplier' => 'Pemasok',
        'date' => 'Tanggal',
        'reference' => 'Referensi',
        'status' => 'Status',
        'payment_method' => 'Metode Pembayaran',
        'amount_received' => 'Jumlah Diterima',
        'note' => 'Catatan (Jika Diperlukan)',
        'update_purchase' => 'Perbarui Pembelian',
        'status_options' => [
            'pending' => 'Tertunda',
            'ordered' => 'Dipesan',
            'completed' => 'Selesai'
        ]
    ],
    'create' => [
        'title' => 'Buat Pembelian',
        'supplier' => 'Pemasok',
        'date' => 'Tanggal',
        'reference' => 'Referensi',
        'status' => 'Status',
        'payment_method' => 'Metode Pembayaran',
        'amount_paid' => 'Jumlah Dibayar',
        'note' => 'Catatan (Jika Diperlukan)',
        'create_purchase' => 'Buat Pembelian',
        'select' => '-Pilih-',
        'status_options' => [
            'pending' => 'Tertunda',
            'ordered' => 'Dipesan',
            'completed' => 'Selesai'
        ]
    ],
    'payment' => [
        'create' => [
            'title' => 'Buat Pembayaran',
            'amount' => 'Jumlah',
            'payment_method' => 'Metode Pembayaran',
            'note' => 'Catatan (Jika Diperlukan)',
            'create_payment' => 'Buat Pembayaran',
            'select' => '-Pilih-',
        ],
        'edit' => [
            'title' => 'Edit Pembayaran',
            'amount' => 'Jumlah',
            'payment_method' => 'Metode Pembayaran',
            'note' => 'Catatan (Jika Diperlukan)',
            'update_payment' => 'Perbarui Pembayaran',
        ],
        'index' => [
            'title' => 'Daftar Pembayaran',
            'add_payment' => 'Tambah Pembayaran',
            'reference' => 'Referensi',
            'amount' => 'Jumlah',
            'payment_method' => 'Metode Pembayaran',
            'date' => 'Tanggal',
            'note' => 'Catatan',
            'status' => 'Status',
            'actions' => 'Aksi',
        ],
    ],
    'datatable' => [
        'purchase' => [
        'tools' => [
            'sEmptyTable' => 'Tidak ada data yang tersedia pada tabel ini',
            'sInfo' => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            'sInfoEmpty' => 'Menampilkan 0 sampai 0 dari 0 data',
            'sInfoFiltered' => '(disaring dari _MAX_ entri keseluruhan)',
            'sInfoPostFix' => '',
            'sInfoThousands' => '.',
            'sLengthMenu' => 'Tampilkan _MENU_ data',
            'sLoadingRecords' => 'Memuat...',
            'sProcessing' => 'Sedang diproses...',
            'sSearch' => 'Cari:',
            'sZeroRecords' => 'Tidak ditemukan data yang sesuai',
            'oPaginate' => [
                'sFirst' => 'Pertama',
                'sLast' => 'Terakhir',
                'sNext' => 'Selanjutnya',
                'sPrevious' => 'Sebelumnya'
                ],
            'oAria' => [
                'sSortAscending' => ': aktifkan untuk mengurutkan kolom ke atas',
                'sSortDescending' => ': aktifkan untuk mengurutkan kolom menurun'
            ]
            ],

            'columns' => [
                'reference' => 'Referensi',
                'date' => 'Tanggal',
                'supplier' => 'Pemasok',
                'status' => 'Status',
                'total_amount' => 'Nominal',
                'paid_amount' => 'Nominal Dibayar',
                'due_amount' => 'Nominal Sisa',
                'payment_status' => 'Status Pembayaran',
                'action' => 'Tindakan',

            ],
            'buttons' => [
                'excel' => 'Excel',
                'print' => 'Cetak',
                'reset' => 'Reset',
                'reload' => 'Muat Ulang'
            ]
            ],
        'payment' => [
            'tools' => [
                'sEmptyTable' => 'Tidak ada data yang tersedia pada tabel ini',
                'sInfo' => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                'sInfoEmpty' => 'Menampilkan 0 sampai 0 dari 0 data',
                'sInfoFiltered' => '(disaring dari _MAX_ entri keseluruhan)',
                'sInfoPostFix' => '',
                'sInfoThousands' => '.',
                'sLengthMenu' => 'Tampilkan _MENU_ data',
                'sLoadingRecords' => 'Memuat...',
                'sProcessing' => 'Sedang diproses...',
                'sSearch' => 'Cari:',
                'sZeroRecords' => 'Tidak ditemukan data yang sesuai',
                'oPaginate' => [
                    'sFirst' => 'Pertama',
                    'sLast' => 'Terakhir',
                    'sNext' => 'Selanjutnya',
                    'sPrevious' => 'Sebelumnya'
                    ],
                'oAria' => [
                    'sSortAscending' => ': aktifkan untuk mengurutkan kolom ke atas',
                    'sSortDescending' => ': aktifkan untuk mengurutkan kolom menurun'
                ]
                ],

                'columns' => [
                    'date' => 'Tanggal',
                    'reference' => 'Referensi',
                    'customer_name' => 'Nama Pelanggan',
                    'amount' => 'Jumlah',
                    'payment_method' => 'Metode Pembayaran',
                    'action' => 'Tindakan',
                    'customer' => 'Pelanggan',
                    'status' => 'Status',
                    'total_amount' => 'Total',
                    'paid_amount' => 'Dibayar',
                    'due_amount' => 'Sisa',
                    'payment_status' => 'Status Pembayaran',
                ],
                'buttons' => [
                    'excel' => 'Excel',
                    'print' => 'Cetak',
                    'reset' => 'Reset',
                    'reload' => 'Muat Ulang'
                ]
        ]
    ],
    'action' => [
        'show_payment' => 'Lihat pembayaran',
        'add_payment' => 'Tambah pembayaran',
        'edit' => 'Ubah',
        'details' => 'Detail',
        'delete' => 'Hapus',
        'delete_confirmation' => 'Apakah anda yakin untuk menghapus data ini secara permanen?',
    ]
];
