@extends('layouts.app')

@section('title', 'Product Details')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('paymentgateway.index') }}">Products</a></li>
        <li class="breadcrumb-item active">Details</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row mb-3">
            <div class="col-md-12">
                {{-- {!! \Milon\Barcode\Facades\DNS1DFacade::getBarCodeSVG($product->product_code, $product->product_barcode_symbology, 2, 110) !!} --}}
            </div>
        </div>

    </div>
@endsection