<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtilityController extends Controller
{
    public function encryptData($data)
    {
        $key = env('LOCKKEY');

        $encryptedData = encryptWithKey($data, $key);

        return $encryptedData;
    }

    public function decryptData($data)
    {
        $key = env('LOCKKEY');

        $decryptedData = decryptWithKey($data, $key);

        return $decryptedData;
    }
}
