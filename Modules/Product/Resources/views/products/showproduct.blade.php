@extends('layouts.app')

@section('title', __('products.details'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('products.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('products.products') }}</a></li>
        <li class="breadcrumb-item active">{{ __('products.details') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
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
            <div class="row">
                <div class="col-12">
                    <livewire:search-product/>
                </div>
            </div>
        </div>
        <div wire:loading.flex class="col-12 position-absolute justify-content-center align-items-center" style="top:0;right:0;left:0;bottom:0;background-color: rgba(255,255,255,0.5);z-index: 99;">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-9">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="product-details">
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.code') }}</span>
                                <span id="product_code" class="detail-value"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.barcode_symbology') }}</span>
                                <span id="barcode_symbology" class="detail-value"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.product_name') }}</span>
                                <span id="product_name" class="detail-value"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.category') }}</span>
                                <span id="category_name" class="detail-value"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.cost') }}</span>
                                <span id="product_cost" class="detail-value"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.price') }}</span>
                                <span id="product_price" class="detail-value"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.quantity') }}</span>
                                <span id="product_quantity_unit" class="detail-value"></span>
                            </div>
                            {{-- <div class="detail-item">
                                <span class="detail-label">{{ __('products.stock_worth') }}</span>
                                <span id="stock_worth" class="detail-value"></span>
                            </div> --}}
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.alert_quantity') }}</span>
                                <span id="product_stock_alert" class="detail-value"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.tax') }}</span>
                                <span id="product_order_tax" class="detail-value"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.tax_type') }}</span>
                                <span id="product_tax_type" class="detail-value"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.note') }}</span>
                                <span id="product_note" class="detail-value"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('products.barcode_name') }}</span>
                                <span id="barcode_container" class="detail-value"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        <img id ="productImage" src="{{ asset('images/default.png') }}" alt="Product Image" class="img-fluid img-thumbnail mb-2 fixed-size-img">
                    </div>
                </div>
            </div>

        </div>
        {{-- @endif --}}
    </div>
@endsection

@push('page_scripts')
<script>


    document.addEventListener('DOMContentLoaded', function () {
        var beepSound = new Audio("{{ asset('sounds/beep.mp3') }}");
        var klikSound = new Audio("{{ asset('sounds/klik.mp3') }}");


        window.addEventListener('productCheckSelected',function(event)  {
            const product = event.detail[0].product;
            const category = event.detail[0].category;
            const image = event.detail[0].image;
            // Contoh: menampilkan data produk di konsol
            console.log('Produk dipilih:', image);
            // document.getElementById('stock_worth').innerText = @json(__('products.cost'), JSON_UNESCAPED_UNICODE) + ' : ' + formatRupiah((parseInt(product.product_cost) * parseInt(product.product_quantity)) , 'Rp. ') + ' \n ' + @json(__('products.price'), JSON_UNESCAPED_UNICODE) + ' : ' + formatRupiah((parseInt(product.product_price) * parseInt(product.product_quantity)), 'Rp. ') + ' \n ' + @json(__('products.profit'), JSON_UNESCAPED_UNICODE) + ' : ' + formatRupiah(((parseInt(product.product_price) * parseInt(product.product_quantity)) - (parseInt(product.product_cost) * parseInt(product.product_quantity))), 'Rp. ') + ' \n';
            document.getElementById('product_name').innerText = product.product_name;
            document.getElementById('product_code').innerText = product.product_code;

            document.getElementById('barcode_symbology').innerText = product.barcode_symbology;
            document.getElementById('category_name').innerText = category.category_name;
            document.getElementById('product_cost').innerText = product.product_cost;
            document.getElementById('product_price').innerText = product.product_price;

            document.getElementById('product_quantity_unit').innerText = product.product_price + ' ' + product.product_unit;
            document.getElementById('product_stock_alert').innerText = product.product_stock_alert;
            document.getElementById('product_order_tax').innerText = product.product_order_tax ?? 'NA';
            document.getElementById('product_tax_type').innerText = product.product_tax_type ?? 'NA';
            document.getElementById('product_note').innerText = product.product_note;

            const barcodeElement = document.getElementById('barcode_container');
            if (!barcodeElement) {
                console.error("Barcode container not found.");
                return;
            }
            // Endpoint Laravel untuk menghasilkan barcode SVG
            const barcodeUrl = `/barcode/generate?product_code=${encodeURIComponent(product.product_code)}&barcode_symbology=${encodeURIComponent(product.product_barcode_symbology)}`;
            // Ambil SVG menggunakan fetch
            fetch(barcodeUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Failed to fetch barcode.");
                    }
                    return response.text();
                })
                .then(svg => {
                    // Sisipkan SVG ke dalam elemen target
                    barcodeElement.innerHTML = svg;
                })
                .catch(error => {
                    console.error("Error generating barcode:", error);
                });

            const productImage = document.getElementById('productImage'); // Dapatkan elemen gambar
            if (productImage) {
                productImage.src = image; // Perbarui atribut src
            }

        });

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            //tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? ',' : '';
                rupiah += separator + ribuan.join(',');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            rupiahDecial = rupiah + '.00'
            return prefix == undefined ? rupiahDecial : (rupiahDecial ? 'Rp. ' + rupiahDecial : '');
        }

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
            let inputField = document.getElementById('productcode');
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
</script>
@endpush


@push('page_css')
<style>
    .fixed-size-img {
    width: 100%;     /* Mengatur gambar agar lebar 100% dari lebar div */
    height: auto;    /* Menyesuaikan tinggi untuk menjaga rasio */
    object-fit: cover; /* Menjamin gambar memenuhi area div jika diperlukan */
}
    .product-details {
        display: flex;
        flex-wrap: wrap;
    }
    .detail-item {
        width: 100%;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }
    .detail-label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }
    .detail-value {
        display: block;
    }
    @media (min-width: 768px) {
        .detail-item {
            width: 50%;
            padding-right: 15px;
        }
    }
    @media (min-width: 992px) {
        .detail-item {
            width: 33.33%;
        }
    }
</style>
@endpush
