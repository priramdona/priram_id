@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="text-center mb-4">Syarat dan Ketentuan</h1>

                    <!-- Pendahuluan -->
                    <div class="section">
                        <h2>1. Pendahuluan</h2>
                        <p>Selamat datang di aplikasi kasir kami. Dengan menggunakan aplikasi ini, Anda menyetujui syarat dan ketentuan penggunaan berikut. Syarat ini berlaku untuk semua pengguna, baik pengguna utama maupun tambahan yang diatur dalam sistem manajemen pengguna.</p>
                    </div>

                    <!-- Fitur Aplikasi -->
                    <div class="section">
                        <h2>2. Fitur Aplikasi</h2>
                        <ul>
                            <li><strong>Kamera untuk Scan Barcode:</strong> Pengguna dapat memanfaatkan kamera perangkat untuk melakukan pemindaian kode barcode pada produk.</li>
                            <li><strong>Pembayaran Online:</strong> Pembayaran dapat dilakukan secara online melalui integrasi dengan layanan Xendit. Fitur ini akan mempengaruhi saldo keuangan yang tercatat pada akun pengguna.</li>
                            <li><strong>Manajemen Produk:</strong> Pengguna dapat menambah, mengubah, dan menghapus informasi seputar produk serta mengelompokan kategori produk yang tersedia.</li>
                            <li><strong>Penjualan Produk:</strong> Fitur ini memungkinkan pencatatan dan pengelolaan transaksi penjualan, mempengaruhi ketersediaan stok dan saldo jika pembayaran dilakukan secara online.</li>
                            <li><strong>Layanan Pesanan Mandiri (Self-Order Service):</strong> Memungkinkan pembeli untuk melakukan transaksi secara mandiri melalui:
                                <ul>
                                    <li><strong>Belanja Mandiri (Mobile Order):</strong> Pengguna hanya tinggal memeriksa secara rangkuman hasil akhir dari Pesanan yang sudah diatur di tempat area produk, dengan proses pembeli bebas memilih dan mengatur produk pada aplikasi saat belanja sampai menentukan metode pembayaran.</li>
                                    <li><strong>Stay Order:</strong> Pengguna hanya tinggal memantau dan memproses pesanan dibuat oleh pembeli dari tempat yang sudah disediakan penjual dimulai dari pemesann oleh pembeli, proses oleh penjual, hingga diterima pembeli. fitur ini biasanya digunakan untuk penjual yang menyediakan pelayanan seperti resto, cafe, produk siap saji.</li>
                                    <li><strong>Delivery Order:</strong> Pengguna hanya tinggal memantau pesanan yang akan dikirimkan ke lokasi pembeli, pembeli dapat mengatur pesanan hingga metode pembayaran sehingga pengguna dapat melihat serta memenuhi pemesanan yang dilakukan pembeli sebelum proses antar ke lokasi pembeli. fitur ini memerlukan izin akses lokasi pada perangkat agar memberikan lokasi yang akurat untuk perhitungan jarak.</li>
                                </ul>
                            </li>
                            <li><strong>Penawaran:</strong> Penjual dapat mengirimkan penawaran kepada pembeli melalui email, yang dapat dikonfirmasi oleh pembeli hingga proses pembayaran selesai. Fitur ini menyediakan pembayaran online yang sudah di integrasikan berupa Tagihan (Invoice), pembeli dapat menentukan sendiri metode pembayaran yang akan digunakan.</li>
                            <li><strong>Manajemen Pembelian:</strong> Mencatat transaksi pembelian yang memengaruhi jumlah stok barang dari transaksi yang sesungguhnya untuk menyesuaikan kondisi produk serta informasi pemasok.</li>
                            <li><strong>Retur Pembelian dan Penjualan:</strong> Mengatur ulang transaksi pembelian dan penjualan sebelumnya ketika ada pengembalian barang dari transaksi pembelian ataupun penjualan, Hal ini biasanya dilakukan untuk memastikan stok yang akurat.</li>
                            <li><strong>Manajemen Keuangan Pemasukan :</strong> Mencatat pengeluaran dan pemasukan di luar transaksi jual-beli produk, Fitur ini tersedia metode pembayaran Online yang memudahkan pemilik untuk melakukan transaksi pemasukan tambahan.</li>
                            <li><strong>Manajemen Keuangan Pengeluaran :</strong> Mencatat pengeluaran dan pemasukan di luar transaksi jual-beli produk.</li>
                            <li><strong>Penyesuaian Stok (Stok Opnam):</strong> Mengatur dan menyesuaikan kondisi stok barang secara manual sesuai kebutuhan dengan proses Pengurangan serta Penambahan.</li>
                            <li><strong>Manajemen Pelanggan dan Pemasok:</strong> Mencatat data pelanggan dan pemasok secara lengkap untuk kebutuhan bisnis.</li>
                            <li><strong>Pembayaran Online:</strong> Menyediakan daftar metode pembayaran online dan pengaturan biaya tambahan.</li>
                            <li><strong>Laporan Keuangan dan Transaksi:</strong> Terdapat berbagai laporan transaksi yang mencakup penjualan, pembelian, pengeluaran, dan pemasukan.</li>
                            <li><strong>Manajemen Saldo:</strong> Menarik saldo dari hasil transaksi pembayaran online, membutuhkan informasi rekening bank pengguna atau pemilik.</li>
                            <li><strong>Pengaturan Pengguna dan Akses:</strong> Pendaftaran pengguna tambahan dan pengaturan hak akses sesuai dengan peran masing-masing.</li>
                            <li><strong>Pengaturan Sistem:</strong> Mengatur informasi bisnis, bahasa yang digunakan, dan konfigurasi lainnya.</li>
                            <li><strong>Tentang Kami:</strong> Informasi kontak, kebijakan privasi, dan syarat layanan.</li>
                        </ul>
                    </div>

                    <!-- Persetujuan Penggunaan Kamera dan Lokasi -->
                    <div class="section">
                        <h2>3. Persetujuan Penggunaan Kamera, Lokasi dan Media penyimpanan</h2>
                        <ul>
                            <li><strong>Kamera:</strong> Dengan menggunakan izin Kamera, pengguna dapat memanfaatkan perangkat untuk melakukan pemindaian kode barcode pada produk. Fitur ini digunakan untuk fasilitas POS/Kasir dan Pelayanan Belanja Mandiri.</li>
                            <li><strong>Lokasi:</strong> Dengan menggunakan izin Lokasi, Pengguna dapat menentukan titik lokasi secara akurat untuk keperluan fasilitas Pelayanan Mandiri berupa aktifitas Pesan Kirim.</li>
                            <li><strong>Media Penyimpanan:</strong> Dengan menggunakan izin Lokasi, Pengguna dapat mengatur gambar pada produk, profil, dan manajemen pengguna</li>
                        </ul>
                    </div>

                    <!-- Kebijakan Pembayaran dan Saldo -->
                    <div class="section">
                        <h2>4. Kebijakan Pembayaran dan Saldo</h2>
                        <p>Semua transaksi pembayaran yang dilakukan melalui layanan Xendit harus mematuhi syarat dan ketentuan pembayaran yang berlaku. Untuk penarikan saldo, pengguna perlu memberikan informasi rekening yang valid.</p>
                    </div>

                    <!-- Kewajiban Pengguna -->
                    <div class="section">
                        <h2>5. Kewajiban Pengguna</h2>
                        <p>Pengguna bertanggung jawab untuk:</p>
                        <ul>
                            <li>Memastikan data produk, transaksi, pelanggan, dan pemasok dengan benar dan akurat.</li>
                            <li>Mengelola akses pengguna tambahan sesuai dengan hak yang diberikan.</li>
                            <li>Menjaga keamanan data transaksi dan informasi keuangan yang tersimpan dalam aplikasi.</li>
                            <li>Memastikan infrormasi yang benar yang sesuai dengan identitas pengguna.</li>
                            <li>Menyertakan kontak yang masih aktif untuk Email dan Nomor Telepon.</li>
                        </ul>
                    </div>

                    <!-- Ketentuan Penggunaan Self-Order Service -->
                    <div class="section">
                        <h2>6. Ketentuan Penggunaan Self-Order Service (Pelayanan Mandiri)</h2>
                        <p>Self-Order Service mencakup layanan pemesanan Mobile Order (Pemesanan Belanja Mandiri), Stay Order (Pemesanan Ditempat), dan Delivery Order (Pemesanan Pengiriman), yang diatur sebagai berikut:</p>
                        <ul>
                            <li>Pengguna wajib memverifikasi data serta produk yang diinput oleh pembeli.</li>
                            <li>Dalam Delivery Order (Pesanan Pengiriman), lokasi yang dimasukkan pembeli harus benar dan akurat agar pesanan sampai sesuai dengan alamat yang diinginkan dan perhitungan jarak sebagai acuan pengguna.</li>
                            <li>Pemesanan yang dilakukan oleh pembeli sepenuhnya menjadi tanggung jawab pengguna untuk memprosesnya.</li>
                        </ul>
                    </div>

                    <!-- Perubahan Syarat dan Ketentuan -->
                    <div class="section">
                        <h2>7. Perubahan Syarat dan Ketentuan</h2>
                        <p>Syarat dan ketentuan ini dapat berubah sewaktu-waktu sesuai dengan kebijakan. Setiap perubahan akan diinformasikan melalui notifikasi pada aplikasi serta pengiriman pada Surel Email yang didaftarkan.</p>
                    </div>

                    <!-- Pembatalan dan Pengembalian -->
                    <div class="section">
                        <h2>8. Pembatalan dan Pengembalian</h2>
                        <p>Kebijakan pengembalian dan pembatalan hanya berlaku untuk metode pembayaran offline, dan pengaturan retur disesuaikan dengan syarat yang ditentukan pada masing-masing transaksi.</p>
                    </div>

                    <!-- Kontak -->
                    <div class="section">
                        <h2>9. Kontak</h2>
                        <p>Untuk informasi lebih lanjut atau pertanyaan mengenai penggunaan aplikasi, pengguna dapat menghubungi tim dukungan kami melalui fitur "Kontak Kami" di aplikasi.</p>
                    </div>

                    <!-- Penyelesaian Perselisihan -->
                    <div class="section">
                        <h2>10. Penyelesaian Perselisihan</h2>
                        <p>Setiap perselisihan yang timbul dari penggunaan aplikasi ini akan diselesaikan sesuai dengan hukum yang berlaku di wilayah Indonesia.</p>
                    </div>

                    <!-- Tombol Cetak -->
                    <div class="text-center print-btn">
                        <button class="btn btn-primary" onclick="window.print()">Cetak Halaman</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


@endsection
@push('page_css')
<style>
    body { font-family: Arial, sans-serif; }
    h1, h2 { color: #0056b3; }
    .section { margin-bottom: 1.5rem; }
    .container { max-width: 800px; }
    .print-btn { margin-top: 20px; }
</style>
@endpush
