<?php

return [
    'list' => [
        'error' => [
            'title' => 'Menunggu Pembayaran',
            'text' => 'Tidak dapat proses sementara menunggu pembayaran'
        ]
        ],
    'home' => 'Beranda',
    'selforders' => 'Daftar Pesanan Mandiri',
    'selforder' => 'Pesanan Mandiri',
    'process' => [
        'scan' => 'Pindai Kode QR',
        'info' => 'Silakan Pindai Hasil Pemesanan Kode QR untuk Melanjutkan Proses Pemesanan Mandiri',
    ],
    'ordered' => [
        'result_info' => 'Tunjukan QR Code kepada Konter pada saat Mengemas dan Cetak Struk'
    ],
    'lists' => [
        'reference' => 'Referensi',
        'customer' => 'Pelanggan',
        'status' => 'Status',
        'total_amount' => 'Total Nominal',
        'paid_amount' => 'Nominal Bayar',
        'payment_method' => 'Metode Pembayaran',
        'code' => 'Kode',
        'due_amount' => 'Sisa Bayar',
        'payment_status' => 'Status Pembayaran',
        'action_modal_label' => 'Pilih tindakan yang ingin Anda lakukan',
        'action_process' => 'Proses Pesanan',
        'action_cancel' => 'Batal',
    ],
    'enter' =>[
        'welcome'=> 'Selamat Datang!',
        'info_welcome'=> 'Lengkapi Informasi Anda',
        'button_enter'=> 'Masuk',
        'mobile' =>[
            'first_name' => 'Nama Depan',
            'last_name' => 'Nama Belakang',
            'phone' => 'Handphone',
            'email' => 'Email',
            'gender' => 'Jenis Kelamin',
            'gender_select' => 'Pilih',
            'gender_male' => 'Laki-laki',
            'gender_female' => 'Perempuan',
            ]
        ],
    'step' =>[
        'mobile' =>[
            '1' => 'Pelanggan Pindai Barcode yang sudah disediakan sebelum belanja',
            '2' => 'Pelanggan Memilih dan Pindai Barcode pada Area Produk',
            '3' => 'Pelanggan Mengatur Jumlah dari Produk yang telah dipindai',
            '4' => 'Setelah selesai belanja, Pelanggan melakukan proses metode pembayaran',
            '5' => 'Konter Pindai hasil Belanja dan melakukan pengecekan Metode Pembayaran serta Barang yang diambil',
            '6' => 'Tahap akhir, Konter mengemas barang yang sudah diselesaikan pelanggan',
            ]
        ],
    'type' => [
    'name' => 'Nama',
    'descriptions' => 'Deskripsi',
    'value_active' => 'Aktif',
    'value_not_active' => 'Tidak Aktif',
    ],
    'business' => [
        'subject' => 'Judul',
        'information' => 'Informasi',
        'status' => 'Status',
        'status_active' => 'Aktif',
        'status_notactive' => 'Tidak Aktif',
        'status_select' => 'Pilih',
        'button_update' => 'Ubah Pengaturan',
        'info_updated' => 'Pengaturan telah Dirubah!',

    ],

    'info_attention' => 'Perhatian!',
    'info_qrcode_mobile' => 'Untuk menghindari resiko yang berlebihan.. QR Code akan ini DINAMIS berubah setiap harinya.. Pastikan anda Cetak setiap hari atau menampilkan QR Code dengan Link yang sudah tersedia.',
    'info_qrcode_show_mobile' => 'Cetak dan tampilkan kode QR untuk dipindai pelanggan saat mulai berbelanja.',
    'info_qrcode_action_mobile' => 'Unduh atau Salin Tautan Pembuat Kode QR Dinamis',
    'button_download' => 'Unduh QR Code.',
    'button_copy' => 'Salin Tautan Pembuat QR Code',
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
    ]

];
