<?php

return [
    'action' => 'Tindakan',
    'create_income' => 'Buat Pendapatan',
    'update_income' => 'Perbarui Pendapatan',
    'reference' => 'Referensi',
    'date' => 'Tanggal',
    'category' => 'Kategori',
    'customer' => 'Pelanggan',
    'amount' => 'Jumlah',
    'incomes_count' => 'Jumlah Pemasukan',
    'details' => 'Detail',
    'payment_method' => 'Metode Pembayaran',
    'payment_channel' => 'Saluran Pembayaran',
    'ovo_phone_number' => 'Nomor Telepon OVO',
    'process_to_payment' => 'Proses ke Pembayaran',
    'payment_fee' => 'Biaya Pembayaran',
    'ppn' => 'PPN',
    'application_fee' => 'Biaya Aplikasi',
    'grand_total' => 'Total Keseluruhan',
    'select_category' => 'Pilih Kategori',
    'not_registered' => 'Belum Terdaftar',
    'select' => 'Pilih',
    'payment_by' => 'Pembayaran oleh',
    'payment_information' => 'Jumlah akan secara otomatis ditambahkan ke Saldo Anda setelah proses penyelesaian Settlement',
    'new_transaction' => 'Transaksi Baru',
    'home' => 'Beranda',
    'incomes' => 'Pendapatan',
    'add' => 'Tambah',
    'continue_payment' => [
        'customer' => [
            'error' => [
                'title' => 'Kesalahan Pelanggan',
                'text' => 'Silakan pilih pelanggan',
            ]
        ],
        'category' => [
            'error' => [
                'title' => 'Kesalahan Kategori',
                'text' => 'Silakan pilih kategori',
            ]
        ],
        'customer_selforder' => [
            'error' => [
                'title' => 'Kesalahan Informasi',
                'text' => 'Silakan lengkapi informasi untuk Metode Pembayaran ini',
            ]
        ],
        'ovo' => [
            'phone_error' => [
                'title' => 'Kesalahan Nomor Telepon',
                'text' => 'Silakan Periksa Nomor Telepon terlebih dahulu!',
            ]
            ],
        'change_payment'=> [
            'title' => 'Ganti Pembayaran',
            'text' => 'Apakah anda ingin mengganti Metode Pembayaran?',
            'button_confirm' => 'Konfirmasi',
            'button_cancel' => 'Batal',
        ],
        'payment_confirmation' => [
            'cancelled' => [
                'title' => 'Dibatalkan',
                'text' => 'Proses pembayaran telah dibatalkan',
            ],
            'error' => [
                'title' => 'Pembayaran Bermasalah',
                'text' => 'Proses gagal, silahkan coba lagi',
            ],
            'success' => [
                'title' => 'Pembayaran Berhasil',
                'text' => 'Pembayaran telah berhasil!',
            ],
            'title' => 'Konfirmasi Pembayaran',
            'text' => 'Apakah Anda ingin melanjutkan pembayaran ini?',
            'button_confirm' => 'Konfirmasi',
            'button_cancel' => 'Batal',
            'confirmed' => [
                'title' => 'Informasi Pembayaran Penting',
                'html' => 'Pembayaran ini Online, Pastikan Selesaikan Proses Pembayaran',
                'button_proceed' => 'Lanjutkan',
                'button_cancel' => 'Batal',
                'proceed' => [
                    'title' => 'Sedang Diproses',
                    'text' => 'Harap tunggu permintaan sedang diproses',
                    'success' => [
                        'title' => 'Proses sukses',
                        'text' => 'Pembayaran telah dibuat',
                    ]
                ]
            ]
        ],
        'payment_action' => [
            'qrcode' => [
                'lbl_payment_action' => 'Silakan Pindai Kode QR untuk Memproses pembayaran',
            ],
            'account' => [
                'lbl_payment_action_1' => 'Silakan Transfer Ke ',
                'lbl_payment_action_2' => ' Akun Virtual :',
            ],
            'info' => [
                'lbl_payment_action' => 'Silakan Cek ke Akun : ',
            ],
            'url' => [
                'lbl_payment_action' => 'Silakan selesaikan persyaratan di bawah ini',
            ],
            'direct' => [
                'lbl_payment_action' => 'Silakan lengkapi ke Akun : ',
            ],
            'links' => [
                'lbl_payment_action' => 'Silakan lengkapi ke Akun : ',
                'input_payment_action_account' => 'Faktur telah dikirim!',
                'input_payment_detail_label' => 'Silakan cek Email atau Whatsapp',
            ]
        ],
        'paylater_invoice' => [
            'error' => [
                'title' => 'Metode pembayaran bermasalah',
                'text' => 'Informasi Pelanggan harus diisi untuk menggunakan Metode Pembayaran ini',
            ],
            'confirmation' => [
                'title' => 'Konfirmasi informasi Pelanggan : ',
                'html_name' => ' Nama  :',
                'html_phone' => ' Telepon  :',
                'html_email' => ' Email  :',
                'html_info' => 'Silahkan Cek dan Konfirmasi informasi Pelanggan',
                'button_confirm' => 'Ya, Konfirmasi!',
                'button_cancel' => 'Tidak, Batal!',
            ]
        ],
        'new_transaction' => [
            'title' => 'Lanjutkan ke transaksi baru?',
            'text' => 'Pembayaran belum diterima, Cek status pembayaran di daftar penjualan',
            'button_confirm' => 'Konfirmasi',
            'button_wait' => 'Tunggu',
        ],
        'waiting_payment' => [
            'title' => 'Menunggu Pembayaran',
            'text' => 'Pembayaran belum diterima',
        ],
        'amount' => [
            'invalid_min' => [
                'title' => 'Nominal Tidak Valid',
                'text' => 'Minimal nominal tidak valid',
            ],
            'invalid_max' => [
                'title' => 'Nominal Tidak Valid',
                'text' => 'Maksimal nominal tidak valid',
            ],
            'error' => [
                'title' => 'Kesalahan Nilai Nominal',
                'text' => 'Silakan masukan nominal',
            ]
        ]
    ],
    'confirm_delete' => 'Apakah Anda yakin? Ini akan menghapus data secara permanen!', // Tambahan
    'action_disabled_payment_online' => 'Aksi Dinonaktifkan jika Pembayaran Online', // Tambahan
    'income_categories' => 'Kategori Pendapatan', // Tambahan
    'add_category' => 'Tambah Kategori', // Tambahan
    'create_category' => 'Buat Kategori', // Tambahan
    'category_name' => 'Nama Kategori', // Tambahan
    'category_description' => 'Deskripsi', // Tambahan
    'create' => 'Buat', // Tambahan
    'datatable' => [
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
];
