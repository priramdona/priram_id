<?php

return [
    'title' => 'Syarat dan Ketentuan',
    'intro' => 'Selamat datang di aplikasi kami! Dengan menggunakan aplikasi ini, Anda setuju untuk mematuhi syarat dan ketentuan yang berlaku. Harap baca dengan seksama.',
    'features' => [
        'product_management' => 'Menu Produk: Untuk Manajemen produk dengan aktivitas Tambah, Edit, Hapus informasi barang hingga penetapan satuan dan sudah dikelompokkan dengan kategori.',
        'sales_menu' => 'Menu Penjualan: Untuk transaksi Barang keluar dan uang masuk, tersedia dengan media pembayaran online. Menu ini menggunakan kamera sebagai alat bantu untuk pemindai barcode.',
        'self_order_service' => [
            'intro' => 'Menu Layanan Pesanan Mandiri: Untuk transaksi Barang keluar dan uang masuk langsung diproses pembeli. Terdiri dari tiga jenis layanan:',
            'mobile_order' => 'a. Mobile Order (Pesan Keliling): Pembeli melakukan transaksi di tempat penjual dari pengambilan barang, mengatur barang hingga memilih metode pembayaran.',
            'stay_order' => 'b. Stay Order (Pesan Ditempat): Pembeli melakukan transaksi di luar tempat penjual, mengatur barang yang akan dimonitor oleh admin sampai pemesanan cukup.',
            'delivery_order' => 'c. Delivery Order (Pesan Kirim): Pengiriman barang dari penjual ke pembeli, mengatur alamat pengiriman serta metode pembayaran.',
        ],
        'offer_menu' => 'Menu Penawaran: Untuk mengirimkan penawaran produk dengan informasi yang sudah disesuaikan. Tersedia media pembayaran online berupa Invoice.',
        'purchase_menu' => 'Menu Pembelian: Untuk transaksi barang masuk dan uang keluar, menambah kapasitas produk dengan pencatatan pemasok.',
        'return_purchase' => 'Menu Retur Pembelian: Transaksi barang keluar untuk dokumentasi pengembalian barang.',
        'return_sale' => 'Menu Retur Penjualan: Transaksi barang masuk untuk dokumentasi pengembalian barang.',
        'expenses_menu' => 'Menu Pengeluaran: Untuk pencatatan pengeluaran keuangan.',
        'income_menu' => 'Menu Pemasukan: Untuk pencatatan pemasukan keuangan.',
        'stock_adjustment' => 'Menu Penyesuaian Stock: Untuk penyesuaian jumlah barang.',
        'parties_menu' => 'Menu Pihak: Menyimpan informasi biodata pelanggan dan pemasok.',
        'online_payment' => 'Menu Pembayaran Online: Media pembayaran online untuk transaksi dan informasi terkait pembayaran.',
        'report_menu' => 'Menu Laporan: Laporan rekap transaksi seperti laba rugi, pembayaran, penjualan, pembelian, retur penjualan, retur pembelian.',
        'balance_management' => 'Menu Manajemen Saldo: Media untuk menarik keuangan virtual melalui transfer ke Bank atau E-Wallet.',
        'user_management' => 'Menu Manajemen Pengguna: Fitur untuk mengatur pengguna tambahan dan hak akses mereka.',
        'about_us' => 'Menu Tentang Kami: Fasilitas kontak kami dengan pilihan Keluhan, Umpan balik, dan Pertanyaan.',
    ],
    'permissions' => [
        'camera' => 'Akses kamera diperlukan untuk memindai barcode pada fitur Kasir dan Pelayanan Mandiri.',
        'location' => 'Akses lokasi diperlukan untuk akurasi pengiriman pada fitur Delivery Order.',
        'media' => 'Akses media dibutuhkan untuk gambar pada produk, profil, dan pengguna.',
        'customer_info' => 'Akses informasi pelanggan diperlukan untuk kebutuhan transaksi dan media pembayaran online.',
        'supplier_info' => 'Akses informasi pemasok diperlukan untuk pencatatan transaksi terkait pemasok.',
        'user_info' => 'Akses informasi pengguna diperlukan untuk transaksi pembayaran online dan komunikasi dengan pelanggan serta pemasok.',
        'email_media' => 'Media email digunakan untuk pengiriman invoice, kontak kami, dan beberapa transaksi lainnya.',
    ],
    'acceptance' => 'Dengan melanjutkan penggunaan aplikasi ini, Anda setuju dengan semua syarat dan ketentuan yang tertera.',
];
