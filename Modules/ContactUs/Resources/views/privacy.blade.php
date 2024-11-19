@extends('layouts.app')

@section('title', 'Kebijakan Privasi')
@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active">Kebijakan Privasi</li>
    </ol>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                <h1 class="text-center mb-4">Kebijakan Privasi</h1>

                <!-- Pendahuluan -->
                <div class="section">
                    <h2>1. Pendahuluan</h2>
                    <p>Privasi Anda sangat penting bagi kami. Kebijakan privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, mengungkapkan, dan melindungi informasi pribadi Anda saat menggunakan aplikasi kasir ini.</p>
                </div>

                <!-- Informasi yang Kami Kumpulkan -->
                <div class="section">
                    <h2>2. Informasi yang Kami Kumpulkan</h2>
                    <p>Kami mengumpulkan informasi dari Anda untuk menyediakan, memperbaiki, dan mengembangkan layanan kami. Jenis informasi yang kami kumpulkan meliputi:</p>
                    <ul>
                        <li><strong>Informasi Pribadi:</strong> Seperti nama, alamat email, nomor telepon, dan informasi rekening bank untuk transaksi online.</li>
                        <li><strong>Informasi Penggunaan Aplikasi:</strong> Data tentang cara Anda menggunakan aplikasi, termasuk fitur yang sering Anda gunakan.</li>
                        <li><strong>Informasi Lokasi:</strong> Jika Anda menggunakan fitur Delivery Order, kami mengakses lokasi perangkat Anda untuk memfasilitasi pengiriman pesanan.</li>
                        <li><strong>Data Perangkat:</strong> Kami mungkin mengumpulkan informasi mengenai perangkat Anda seperti jenis perangkat, versi OS, dan pengaturan perangkat yang diperlukan untuk mendukung layanan kami.</li>
                    </ul>
                </div>

                <!-- Cara Kami Menggunakan Informasi -->
                <div class="section">
                    <h2>3. Cara Kami Menggunakan Informasi</h2>
                    <p>Informasi yang kami kumpulkan digunakan untuk berbagai tujuan seperti:</p>
                    <ul>
                        <li>Memproses transaksi, termasuk pembayaran online dan pembaruan stok produk.</li>
                        <li>Menyediakan dan meningkatkan layanan kami, serta mengembangkan fitur baru sesuai dengan kebutuhan pengguna.</li>
                        <li>Mengirim notifikasi, termasuk konfirmasi pesanan, penawaran, dan informasi produk kepada pengguna.</li>
                        <li>Mendukung fitur pengiriman melalui aplikasi dengan informasi lokasi yang akurat.</li>
                        <li>Mematuhi ketentuan hukum dan peraturan yang berlaku, serta melindungi hak dan keamanan pengguna.</li>
                    </ul>
                </div>

                <!-- Bagaimana Kami Membagikan Informasi Anda -->
                <div class="section">
                    <h2>4. Bagaimana Kami Membagikan Informasi Anda</h2>
                    <p>Kami tidak membagikan informasi pribadi Anda kepada pihak ketiga kecuali dalam keadaan berikut:</p>
                    <ul>
                        <li><strong>Penyedia Layanan:</strong> Kami bekerja sama dengan penyedia layanan pihak ketiga yang membantu kami menjalankan fungsi-fungsi aplikasi, seperti layanan pembayaran (misalnya, Xendit) dan layanan pengiriman.</li>
                        <li><strong>Kepatuhan Hukum:</strong> Kami dapat mengungkapkan informasi Anda jika diwajibkan oleh hukum atau untuk melindungi hak, privasi, keselamatan, atau properti kami atau pengguna lainnya.</li>
                    </ul>
                </div>

                <!-- Keamanan Data Anda -->
                <div class="section">
                    <h2>5. Keamanan Data Anda</h2>
                    <p>Kami berkomitmen untuk melindungi keamanan data Anda. Kami menggunakan berbagai tindakan keamanan untuk menjaga data Anda tetap aman, termasuk enkripsi dan pengendalian akses. Namun, kami tidak dapat menjamin sepenuhnya keamanan data di internet.</p>
                </div>

                <!-- Akses dan Pengaturan Informasi Pribadi -->
                <div class="section">
                    <h2>6. Akses dan Pengaturan Informasi Pribadi</h2>
                    <p>Anda memiliki hak untuk mengakses, memperbarui, atau menghapus informasi pribadi yang telah Anda bagikan dengan kami. Jika Anda ingin memperbarui informasi atau memiliki pertanyaan tentang kebijakan privasi ini, Anda dapat menghubungi kami melalui fitur "Kontak Kami" di aplikasi.</p>
                </div>

                <!-- Persetujuan Penggunaan Kamera dan Lokasi -->
                <div class="section">
                    <h2>7. Persetujuan Penggunaan Kamera, Lokasi dan Media Penyimpanan</h2>
                    <p>Dengan menggunakan fitur tertentu pada aplikasi ini, seperti pemindai barcode, fitur pengiriman dan mengatur gambar. Anda memberikan izin bagi aplikasi untuk mengakses kamera, lokasi dan media penyimpanan perangkat Anda sesuai kebutuhan fungsi tersebut.</p>
                </div>

                <!-- Penggunaan Cookie dan Teknologi Serupa -->
                <div class="section">
                    <h2>8. Penggunaan Cookie dan Teknologi Serupa</h2>
                    <p>Kami dapat menggunakan cookie dan teknologi serupa untuk mengumpulkan informasi penggunaan dan preferensi Anda di aplikasi. Cookie membantu kami memberikan pengalaman pengguna yang lebih baik dengan mengenali preferensi dan penggunaan sebelumnya.</p>
                </div>

                <!-- Penyimpanan Data -->
                <div class="section">
                    <h2>9. Penyimpanan dan Penghapusan Data</h2>
                    <p>Kami menyimpan informasi Anda selama akun Anda aktif atau selama diperlukan untuk menyediakan layanan yang Anda minta. Jika Anda ingin menghapus akun dapat dilakukan pada fitur yang sudah kami sediakan di pengaturan akun, kami akan menghapus informasi pribadi sesuai dengan ketentuan yang berlaku, kecuali diwajibkan oleh hukum untuk menyimpannya.</p>
                </div>

                <!-- Perubahan Kebijakan Privasi -->
                <div class="section">
                    <h2>10. Perubahan Kebijakan Privasi</h2>
                    <p>Kebijakan privasi ini dapat diperbarui dari waktu ke waktu. Kami akan memberi tahu Anda tentang perubahan dengan memposting kebijakan yang diperbarui di aplikasi. Penggunaan Anda yang berkelanjutan atas aplikasi setelah perubahan dianggap sebagai persetujuan atas kebijakan yang telah diperbarui.</p>
                </div>

                <!-- Kontak Kami -->
                <div class="section">
                    <h2>11. Kontak Kami</h2>
                    <p>Jika Anda memiliki pertanyaan atau keluhan tentang kebijakan privasi ini, silakan hubungi kami melalui fitur "Kontak Kami" di aplikasi atau melalui alamat email yang tertera di aplikasi.</p>
                </div>

                <!-- Tombol Cetak -->
                <div class="text-center print-btn">
                    <button class="btn btn-primary" onclick="window.print()">Cetak Halaman</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
