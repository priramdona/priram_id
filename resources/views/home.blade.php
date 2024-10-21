@extends('layouts.app')

@section('title', __('home.title'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item active">{{ __('home.breadcrumb') }}</li>
    </ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="my-4">Scan Barcode</h1>
            <div class="scanner-container">
                <div id="reader" class="border p-3"></div>
            </div>
            <div class="barcode-result">
                <label for="barcode-result" class="form-label">Hasil Barcode:</label>
                <input type="text" id="barcode-result" class="form-control" placeholder="Barcode belum terbaca" readonly>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
<script>
    // Deteksi perangkat (mobile atau desktop)
    function isMobile() {
        return /Mobi|Android/i.test(navigator.userAgent);
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Menampilkan hasil barcode ke input field
        document.getElementById("barcode-result").value = decodedText;
    }

    function onScanFailure(error) {
        // Menangani kegagalan scan, Anda bisa log atau abaikan error
        console.warn(`Kode tidak terbaca: ${error}`);
    }

    function startCamera() {
        // Memulai scanner dengan Html5QrcodeScanner
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                // Menyesuaikan dengan perangkat
                aspectRatio: isMobile() ? undefined : 1,
            }
        );
        // Render scan
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }

    // Secara otomatis memulai scanner saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        startCamera();
    });
</script>
    <div class="container-fluid">
        @can('show_total_stats')
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="card border-0">
                    <div class="card-body p-0 d-flex align-items-center shadow-sm">
                        <div class="bg-gradient-primary p-4 mfe-3 rounded-left">
                            <i class="bi bi-cash font-2xl"></i>
                        </div>
                        <div>
                            <div class="text-value text-primary">{{ format_currency($balance) }}</div>
                            <div class="text-muted text-uppercase font-weight-bold small">{{ __('home.balance') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0">
                    <div class="card-body p-0 d-flex align-items-center shadow-sm">
                        <div class="bg-gradient-primary p-4 mfe-3 rounded-left">
                            <i class="bi bi-credit-card font-2xl"></i>
                        </div>
                        <div>
                            <div class="text-value text-primary">{{ format_currency($balance) }}</div>
                            <div class="text-muted text-uppercase font-weight-bold small">{{ __('home.credit') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-6 col-lg-3">
                <div class="card border-0">
                    <div class="card-body p-0 d-flex align-items-center shadow-sm">
                        <div class="bg-gradient-primary p-4 mfe-3 rounded-left">
                            <i class="bi bi-bar-chart font-2xl"></i>
                        </div>
                        <div>
                            <div class="text-value text-primary">{{ format_currency($revenue) }}</div>
                            <div class="text-muted text-uppercase font-weight-bold small">{{ __('home.revenue') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0">
                    <div class="card-body p-0 d-flex align-items-center shadow-sm">
                        <div class="bg-gradient-warning p-4 mfe-3 rounded-left">
                            <i class="bi bi-arrow-return-left font-2xl"></i>
                        </div>
                        <div>
                            <div class="text-value text-warning">{{ format_currency($sale_returns) }}</div>
                            <div class="text-muted text-uppercase font-weight-bold small">{{ __('home.sales_return') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0">
                    <div class="card-body p-0 d-flex align-items-center shadow-sm">
                        <div class="bg-gradient-success p-4 mfe-3 rounded-left">
                            <i class="bi bi-arrow-return-right font-2xl"></i>
                        </div>
                        <div>
                            <div class="text-value text-success">{{ format_currency($purchase_returns) }}</div>
                            <div class="text-muted text-uppercase font-weight-bold small">{{ __('home.purchases_return') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0">
                    <div class="card-body p-0 d-flex align-items-center shadow-sm">
                        <div class="bg-gradient-info p-4 mfe-3 rounded-left">
                            <i class="bi bi-trophy font-2xl"></i>
                        </div>
                        <div>
                            <div class="text-value text-info">{{ format_currency($profit) }}</div>
                            <div class="text-muted text-uppercase font-weight-bold small">{{ __('home.profit') }}</div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        @endcan

        @can('show_weekly_sales_purchases|show_month_overview')
        <div class="row mb-4">
            @can('show_weekly_sales_purchases')
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header">
                        {{ __('home.sales_purchases_last_7_days') }}
                    </div>
                    <div class="card-body">
                        <canvas id="salesPurchasesChart"></canvas>
                    </div>
                </div>
            </div>
            @endcan
            @can('show_month_overview')
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header">
                        {{ __('home.overview_of', ['month' => now()->format('F, Y')]) }}
                    </div>
                    <div class="card-body d-flex justify-content-center">
                        <div class="chart-container" style="position: relative; height:auto; width:280px">
                            <canvas id="currentMonthChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
        </div>
        @endcan

        @can('show_monthly_cashflow')
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        {{ __('home.monthly_cashflow') }}
                    </div>
                    <div class="card-body">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </div>
@endsection

@section('third_party_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js"
            integrity="sha512-asxKqQghC1oBShyhiBwA+YgotaSYKxGP1rcSYTDrB0U6DxwlJjU59B67U8+5/++uFjcuVM8Hh5cokLjZlhm3Vg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@push('page_scripts')
    @vite('resources/js/chart-config.js')
@endpush
