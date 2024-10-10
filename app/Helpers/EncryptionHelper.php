<?php

if (!function_exists('encryptWithKey')) {
    function encryptWithKey($data)
    {
        $cipher = "AES-256-CBC";
        $key = env('LOCKKEY');
        $iv = substr(hash('sha256', $key), 0, 16);
        $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
        return base64_encode($encrypted);
    }
}

if (!function_exists('decryptWithKey')) {
    function decryptWithKey($encryptedData)
    {
        $cipher = "AES-256-CBC";
        $key = env('LOCKKEY');
        $iv = substr(hash('sha256', $key), 0, 16);
        $decrypted = openssl_decrypt(base64_decode($encryptedData), $cipher, $key, 0, $iv);
        return $decrypted;
    }
}
