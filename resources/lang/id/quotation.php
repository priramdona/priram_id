<?php

return [
'breadcrumb' => [
    'quotation' => 'Penawaran',
    'quotations' => 'Daftar Penawaran',
    'add_quotation' => 'Tambah Penawaran',
    'edit_quotation' => 'Ubah Penawaran',
    'home' => 'Beranda',
],
'form' => [
    'reference' => 'Referensi',
    'customer' => 'Pelanggan',
    'date' => 'Tanggal',
    'status' => 'Status',
    'send_invoice' => 'Kirim Faktur',
    'invoice_expiry_date' => 'Tanggal Kadaluarsa Faktur',
    'note' => 'Catatan (Jika Diperlukan)',
    'create_quotation' => 'Buat Penawaran',
    'status_options' => [
        'pending' => 'Menunggu',
        'sent' => 'Kirim ke Email'
    ],
    'update_quotation' => 'Perbarui Penawaran',
    'edit' => 'Ubah',
],
    'datatable' => [
        'add' => 'Tambah Penawaran',
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
                    'reload' => 'Reload'
                ]

            ],

    'sale' => [
        'title' => 'Buat Penjualan Dari Penawaran',
        'make_sale' => 'Buat Penjualan',
        'payment_method' => 'Metode Pembayaran',
        'amount_received' => 'Jumlah Diterima',
        'create_sale' => 'Buat Penjualan',
        'status_options' => [
            'pending' => 'Menunggu',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai'
        ],
        'payment_options' => [
            'cash' => 'Tunai',
            'credit_card' => 'Kartu Kredit',
            'bank_transfer' => 'Transfer Bank',
            'other' => 'Lainnya'
        ]
    ],

    'actions' => [
        'make_sale' => 'Buat Penjualan',
        'send_email' => 'Kirim ke Email',
        'edit' => 'Ubah',
        'details' => 'Detail',
        'delete' => 'Hapus',
        'delete_confirm' => 'Anda yakin? Data akan dihapus secara permanen!',
    ],

    'email' => [
        'title' => 'Penawaran',
        'thanks' => 'Terima Kasih Telah Menjadi Partner!',
        'our_quotation' => 'Penawaran Kami',
        'generated_by' => 'Dibuat oleh Kasir Mulia',
        'product' => 'Produk',
        'price' => 'Harga',
        'qty' => 'Jml',
        'discount' => 'Diskon',
        'tax' => 'Pajak',
        'sub_total' => 'Sub Total',
        'shipping' => 'Pengiriman',
        'total' => 'Total',
        'your_details' => 'Detail Anda',
        'customer_info' => 'Informasi Pelanggan',
        'dear' => 'Yth',
        'interest_msg' => 'Kami berharap Anda tertarik dengan produk kami. Jika Anda ingin memesan produk kami, Untuk informasi lebih lanjut, silakan hubungi Nomor Telepon Kami',
        'or_email' => 'atau Email Kami',
        'additional_note' => 'Catatan Tambahan',
        'invoice_expire' => 'Tanggal Kadaluarsa Faktur',
        'thanks_customer' => 'Terima kasih telah menjadi pelanggan yang baik. Semoga sukses!',
        'powered_by' => 'Dipersembahkan oleh Kasir Mulia'
    ],

    'print' => [
        'title' => 'Detail Penawaran',
        'reference' => 'Referensi',
        'company_info' => 'Info Usaha',
        'customer_info' => 'Info Pelanggan',
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

    'show' => [
        'title' => 'Detail Penawaran',
        'details' => 'Detail',
        'reference' => 'Referensi',
        'print' => 'Cetak',
        'save' => 'Simpan',
        'company_info' => 'Info Usaha',
        'customer_info' => 'Info Pelanggan',
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

];
