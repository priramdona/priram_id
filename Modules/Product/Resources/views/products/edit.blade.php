@extends('layouts.app')

@section('title', __('products.edit_product'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('products.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('products.products') }}</a></li>
        <li class="breadcrumb-item active">{{ __('products.edit_product') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <form id="product-form" action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                    <div class="form-group">
                        <button class="btn btn-primary">{{ __('products.update_product') }} <i class="bi bi-check"></i></button>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_name">{{ __('products.product_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="product_name" required value="{{ $product->product_name }}">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <span><i class="bi bi-camera"></i> {{ __('sales.scanner') }}</span>
                                                <button class="btn btn-link" type="button" id="expandcamera">
                                                    <i class="bi bi-caret-down-fill" id='iconexpandcamera'></i>
                                                </button>
                                            </div>
                                            <div class="card-body" id="cameraview" hidden>
                                                <div id="interactive" name="interactive" class="viewport">
                                                    <video id="video" autoplay></video>
                                                    <div class="scanner-laser"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="product_code">{{ __('products.code') }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" onkeydown="if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') { event.preventDefault(); }" class="form-control" name="product_code" required value="{{ $product->product_code }}">
                                            <button type="button" id="generate-barcode-btn" class="btn btn-primary">{{ __('products.barcode.generate') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category_id">{{ __('products.category') }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="category_id" id="category_id" required>
                                            @foreach(\Modules\Product\Entities\Category::all() as $category)
                                                <option {{ $category->id == $product->category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6" hidden>
                                    <div class="form-group" >
                                        <label for="barcode_symbology">{{ __('products.barcode_symbology') }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="product_barcode_symbology" id="barcode_symbology" required>
                                            {{-- <option {{ $product->product_barcode_symbology == 'C128' ? 'selected' : '' }} value="C128">Code 128</option>
                                            <option {{ $product->product_barcode_symbology == 'C39' ? 'selected' : '' }} value="C39">Code 39</option>
                                            <option {{ $product->product_barcode_symbology == 'UPCA' ? 'selected' : '' }} value="UPCA">UPC-A</option>
                                            <option {{ $product->product_barcode_symbology == 'UPCE' ? 'selected' : '' }} value="UPCE">UPC-E</option> --}}
                                            <option {{ $product->product_barcode_symbology == 'EAN13' ? 'selected' : '' }} value="EAN13">EAN-13</option>
                                            {{-- <option {{ $product->product_barcode_symbology == 'EAN8' ? 'selected' : '' }} value="EAN8">EAN-8</option> --}}
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_cost">{{ __('products.cost') }} <span class="text-danger">*</span></label>
                                        <input id="product_cost" type="text" class="form-control" min="0" name="product_cost" required value="{{ $product->product_cost }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_price">{{ __('products.price') }} <span class="text-danger">*</span></label>
                                        <input id="product_price" type="text" class="form-control" min="0" name="product_price" required value="{{ $product->product_price }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_quantity">{{ __('products.quantity') }} <span class="text-danger">*</span></label>
                                        <input type="number" onkeydown="if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') { event.preventDefault(); }" class="form-control" name="product_quantity" required value="{{ $product->product_quantity }}" min="1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_stock_alert">{{ __('products.alert_quantity') }} <span class="text-danger">*</span></label>
                                        <input type="number" onkeydown="if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') { event.preventDefault(); }" class="form-control" name="product_stock_alert" required value="{{ $product->product_stock_alert }}" min="0">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="product_order_tax">{{ __('products.tax') }}</label>
                                        <input type="number"onkeydown="if(!/^\d*\.?\d{0,2}$/.test(this.value + event.key) && event.key !== 'Backspace') { event.preventDefault(); }" class="form-control" name="product_order_tax" value="{{ $product->product_order_tax }}" min="0" max="100">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="product_tax_type">{{ __('products.tax_type') }}</label>
                                        <select class="form-control" name="product_tax_type" id="product_tax_type">
                                            <option value="" selected>None</option>
                                            <option {{ $product->product_tax_type == 1 ? 'selected' : '' }}  value="1">Exclusive</option>
                                            <option {{ $product->product_tax_type == 2 ? 'selected' : '' }} value="2">Inclusive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="product_unit">{{ __('products.unit') }} <i class="bi bi-question-circle-fill text-info" data-toggle="tooltip" data-placement="top" title="This short text will be placed after Product Quantity."></i> <span class="text-danger">*</span></label>
                                        <select class="form-control" name="product_unit" id="product_unit" required>
                                            <option value="" selected >Select Unit</option>
                                            @foreach(\Modules\Setting\Entities\Unit::all() as $unit)
                                                <option {{ $product->product_unit == $unit->short_name ? 'selected' : '' }} value="{{ $unit->short_name }}">{{ $unit->name . ' | ' . $unit->short_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="product_note">{{ __('products.note') }}</label>
                                <textarea name="product_note" id="product_note" rows="4 " class="form-control">{{ $product->product_note }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="image">{{ __('products.product_images') }}</label>
                                <input type="file"
                                        class="form-control-file"
                                        id="imageInput"
                                        name="image"
                                        accept="image/*"
                                        onchange="previewImage(event)"
                                        title="{{ __('products.no_file_selected') }}">
                                <div class="mt-3">
                                    <!-- Preview Image -->
                                    <img id="imagePreview" src="{{ $product->image_url ?? asset('images/default.png') }}" alt="{{ __('products.no_file_selected') }}" style="max-width: 200px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        var beepSound = new Audio("{{ asset('sounds/beep.mp3') }}");
        var klikSound = new Audio("{{ asset('sounds/klik.mp3') }}");

        Quagga.init({
                inputStream : {
                    name : "Live",
                    type : "LiveStream",
                    target: document.querySelector('#interactive'),
                    constraints: {
                        facingMode: "environment",
                        advanced: [
                            { focusMode: "manual" },
                            { zoom: 4 },
                        ]
                    }
                },
                locator: {
                    patchSize: "small",
                    halfSample: false,
                    debug: {
                        showCanvas: true,
                        showPatches: true,
                        showFoundPatches: true,
                        showSkeleton: true,
                        showLabels: true,
                        showPatchLabels: true,
                        showRemainingPatchLabels: true,
                    }
                },
                area: {
                    top: "30%",
                    right: "30%",
                    left: "30%",
                    bottom: "30%"
                },
                decoder : {
                    readers : ["code_128_reader", "ean_reader"],
                },
                locate: true
            }, function(err) {
                if (err) {
                    return;
                }
                Quagga.start();
            });

        Quagga.onDetected(function(result) {
            var code = result.codeResult.code;
            let inputField = document.getElementById('product_code');
            if(inputField) {
                inputField.value = code;
                inputField.dispatchEvent(new Event('input'));
                klikSound.play();
            }
        });
    });

    $(document).on('click', '#expandcamera', function() {
        var div = $('#cameraview');
        var icon = $('#iconexpandcamera');

        if (div.attr('hidden')) {
            icon.removeClass('bi-caret-down-fill').addClass('bi-caret-up-fill');
            div.removeAttr('hidden');
        } else {
            icon.removeClass('bi-caret-up-fill').addClass('bi-caret-down-fill');
            div.attr('hidden', true);
        }
    });
        function previewImage(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function() {
            const preview = document.getElementById('imagePreview');
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file); // Membaca file sebagai data URL untuk preview
        }
    }

    </script>

    <script src="{{ asset('js/jquery-mask-money.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#product_cost').maskMoney({
                prefix:'{{ settings()->currency->symbol }}',
                thousands:'{{ settings()->currency->thousand_separator }}',
                decimal:'{{ settings()->currency->decimal_separator }}',
            });
            $('#product_price').maskMoney({
                prefix:'{{ settings()->currency->symbol }}',
                thousands:'{{ settings()->currency->thousand_separator }}',
                decimal:'{{ settings()->currency->decimal_separator }}',
            });

            $('#product_cost').maskMoney('mask');
            $('#product_price').maskMoney('mask');

            $('#product-form').submit(function () {
                var product_cost = $('#product_cost').maskMoney('unmasked')[0];
                var product_price = $('#product_price').maskMoney('unmasked')[0];
                $('#product_cost').val(product_cost);
                $('#product_price').val(product_price);
            });
        });
    </script>
@endpush

