<?php

return [
    'title' => 'Detail Pembelian',
    'home' => 'Beranda',
    'purchase_returns' => 'Pengembalian Pembelian',
    'details' => 'Detail',
    'reference' => 'Referensi',
    'print' => 'Cetak',
    'save' => 'Simpan',
    'company_info' => 'Info Usaha',
    'email' => 'Email',
    'phone' => 'Telepon',
    'supplier_info' => 'Info Pemasok',
    'invoice_info' => 'Info Faktur',
    'invoice' => 'Faktur',
    'date' => 'Tanggal',
    'status' => 'Status',
    'payment_status' => 'Status Pembayaran',
    'product' => 'Produk',
    'net_unit_price' => 'Harga Satuan Bersih',
    'quantity' => 'Kuantitas',
    'discount' => 'Diskon',
    'tax' => 'Pajak',
    'sub_total' => 'Subtotal',
    'shipping' => 'Pengiriman',
    'grand_total' => 'Total Keseluruhan',
    'add_purchase_return' => 'Tambah Pengembalian Pembelian',
    'edit_purchase_return' => 'Edit Pengembalian Pembelian',
    'supplier' => 'Pemasok',
    'pending' => 'Menunggu',
    'shipped' => 'Dikirim',
    'completed' => 'Selesai',
    'payment_method' => 'Metode Pembayaran',
    'paid_amount' => 'Jumlah Dibayar',
    'note' => 'Catatan',
    'if_needed' => 'Jika Diperlukan',
    'update_purchase_return' => 'Perbarui Pengembalian Pembelian',
    'create_purchase_return' => 'Buat Pengembalian Pembelian',
    'due_amount' => 'Jumlah Terutang',
    'amount' => 'Jumlah',
    'select' => 'Pilih',
    'edit_payment' => 'Edit Pembayaran',
    'update_payment' => 'Perbarui Pembayaran',
    'show_payments' => 'Tampilkan Pembayaran',
    'add_payment' => 'Tambah Pembayaran',
    'edit' => 'Edit',
    'confirm_delete' => 'Apakah Anda yakin? Ini akan menghapus data secara permanen!',
    'delete' => 'Hapus',
    'datatable' => [
        'purchase_return' => [
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
    ]
];
