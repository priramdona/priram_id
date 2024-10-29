<?php

namespace App\Helpers;

class PhoneHelper
{
    /**
     * Mengonversi nomor telepon ke format E.164 Indonesia.
     *
     * @param string $phone
     * @return string|null
     */
    public static function formatToE164Indonesia($phone)
    {
        // Menghapus semua karakter yang bukan angka
        $phone = preg_replace('/\D/', '', $phone);

        // Jika nomor sudah sesuai dengan format E.164 Indonesia, langsung return
        if (preg_match('/^62\d{9,12}$/', $phone)) {
            return '+' . $phone;
        }

        // Jika nomor dimulai dengan "0", ganti dengan kode negara Indonesia "62"
        if (preg_match('/^0\d{9,12}$/', $phone)) {
            $phone = '62' . substr($phone, 1);
            return '+' . $phone;
        }

        // Jika nomor tidak sesuai format E.164 Indonesia dan tidak dimulai dengan "0"
        if (!preg_match('/^62\d{9,12}$/', $phone)) {
            // Pastikan ada panjang minimum 9 digit setelah "62"
            if (strlen($phone) >= 9 && strlen($phone) <= 12) {
                return '+62' . $phone;
            } else {
                return null; // Mengembalikan null jika nomor tidak valid
            }
        }

        return null;
    }

    public static function formatToLocalIndonesia($phone)
    {
        // Menghapus semua karakter yang bukan angka atau "+"
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // Jika nomor dimulai dengan "+62", hapus "+" lalu ganti "62" dengan "0"
        if (preg_match('/^\+62\d{9,12}$/', $phone)) {
            return '0' . substr($phone, 3);
        }

        // Jika nomor dimulai dengan "62" tanpa "+", ganti dengan "0"
        if (preg_match('/^62\d{9,12}$/', $phone)) {
            return '0' . substr($phone, 2);
        }

        // Jika nomor sudah dimulai dengan "0", langsung return
        if (preg_match('/^0\d{9,12}$/', $phone)) {
            return $phone;
        }

        return null; // Mengembalikan null jika nomor tidak valid
    }
}
