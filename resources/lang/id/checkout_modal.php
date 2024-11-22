<?php

return [
    'confirm_sale' => 'Konfirmasi Penjualan',
    'confirm_selforder' => 'Konfirmasi pembayaran Pelayanan Mandiri',
    'received_amount' => 'Jumlah Diterima',
    'total_amount' => 'Jumlah Total',
    'payment_method' => 'Metode Pembayaran',
    'payment_channel' => 'Pembayaran',
    'select' => 'Pilih',
    'note' => 'Catatan (Jika dibutuhkan)',
    'summary' => [
        'total_products' => 'Jumlah Produk',
        'discount' => 'Diskon',
        'total' => 'Total',
        'shipping' => 'Pengiriman',
        'tax' => 'Pajak',
        'payment_fee' => 'Biaya Pembayaran',
        'ppn' => 'PPN',
        'ppn_from_payment' => '(Dari pembayaran)',
        'free' => 'Gratis',
        'application_fee' => 'Biaya Aplikasi',
        'grand_total' => 'Total Keseluruhan',
        'info' => 'Penting, Memproses pembayaran ini berarti Anda tidak dapat Membatalkan keranjang Anda'
    ],
    'ovo' => [
        'phone_number' => 'Nomor OVO'
    ],
    'submit' => 'Simpan',
    'proceed_payment' => 'Lanjut Pembayaran',
    'close' => 'Tutup',
    'payment_action' => [
        'refresh' => 'Perbarui',
        'account_no' => 'Nomor Akun',
        'account_name' => 'Nama',
        'account_expired' => 'Berlaku hingga',
        'email' => 'EMAIL',
        'whatsapp' => 'WHATSAPP',
        'payment_by' => 'Pembayaran dengan',
        'footer_info' => 'Jumlah akan secara otomatis ditambahkan ke Saldo Anda setelah proses Penyelesaian (Settlement)',
        'footer_info_deducted' => 'Jumlah akan Mengurangi dari Akun anda',
        'button_check_payment' => 'Cek Pembayaran',
        'button_new_transaction' => 'Transaksi Baru',
        'button_change_payment' => 'Ganti Pembayaran',
    ],
    'action' => [
        'selforder' => [
            'info_customer' => 'Wajib melengkapi untuk Metode Pembayaran ini',
            'phone' => 'Nomor Telepon',
            'first_name' => 'Nama Depan',
            'last_name' => 'Nama Belakang',
            'gender' => 'Jenis Kelamin',
            'select' => 'Pilih',
            'male' => 'Wanita',
            'female' => 'Laki-laki',
            'dob' => 'Tanggal Lahir',
            'email' => 'Email',
            'address' => 'Alamat',
            'city' => 'Kota',
            'province' => 'Provinsi',
            'postal_code' => 'Kode Pos',
            'button_close' => 'Tutup',
            'button_proceed' => 'Lanjutkan',
        ],
    ],
    'continue_payment' => [
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
        ]
    ]
];
