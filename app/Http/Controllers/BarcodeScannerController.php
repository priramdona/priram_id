<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarcodeScannerController extends Controller
{
    public function index()
    {
        return view('scan-barcode');
    }
}
