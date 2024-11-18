@extends('layouts.app')

@section('title', __('menu.show_list'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('menu.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('payment-gateways.index') }}">{{ __('menu.paymentgateway') }}</a></li>
        <li class="breadcrumb-item active">{{ __('menu.show_list') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container">
        <h2 class="my-4">{{ __('payment_gateway.payment_channels_info') }}</h2>

        @if($result->isNotEmpty())
            <div class="table-responsive" id="table-wrapper">
                <table class="table table-bordered table-hover" style="table-layout: auto; width: 100%;">
                    <thead class="thead-dark">
                        <tr>
                            <th>{{ __('payment_gateway.name') }}</th>
                            <th>{{ __('payment_gateway.type') }}</th>
                            <th>{{ __('payment_gateway.fee_1') }}</th>
                            <th>{{ __('payment_gateway.fee_2') }}</th>
                            <th>{{ __('payment_gateway.ppn') }} <span class="small ">(From Fees)</span></th>
                            <th>{{ __('payment_gateway.min') }}</th>
                            <th>{{ __('payment_gateway.max') }}</th>
                            <th>{{ __('payment_gateway.process') }}</th>
                            <th>{{ __('payment_gateway.settlement') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($result as $channel)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($channel->image_url)
                                            <img src="{{ $channel->image_url }}" alt="{{ $channel->name }} Image" class="img-fluid me-2" style="max-width: 30px;">
                                        @else
                                            <span>{{ __('payment_gateway.no_image') }}</span>
                                        @endif
                                        <span>{{ $channel->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $channel->type == 'VIRTUAL_ACCOUNT' ? __('payment_gateway.va') : $channel->type }}</td>
                                <td>
                                    @if($channel->fee_type_1 == "%")
                                        {{ round($channel->fee_value_1, 2) }} %
                                    @else
                                        {{ format_currency($channel->fee_value_1) }}
                                    @endif
                                </td>
                                <td>
                                    @if($channel->fee_type_2)
                                        @if($channel->fee_type_2 == "%")
                                            {{ round($channel->fee_value_2, 2) }} %
                                        @else
                                            {{ format_currency($channel->fee_value_2) }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($channel->is_ppn)
                                    <i class="bi bi-check bg-success"></i>
                                    @else
                                        <i class="bi bi-x bg-danger"></i>
                                    @endif
                                </td>
                                <td>{{ format_currency($channel->min) }}</td>
                                <td>{{ format_currency($channel->max) }}</td>
                                <td>{{ $channel->payment_process }}</td>
                                <td>{{ $channel->settlement }} {{ __('payment_gateway.days') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>{{ __('payment_gateway.no_payment_channels') }}</p>
        @endif
    </div>

@endsection

@section('scripts')
<script>
    // JavaScript untuk drag scrolling pada table body
    const tableWrapper = document.getElementById('table-wrapper');
    let isDown = false;
    let startX;
    let scrollLeft;

    tableWrapper.addEventListener('mousedown', (e) => {
        isDown = true;
        tableWrapper.classList.add('active');
        startX = e.pageX - tableWrapper.offsetLeft;
        scrollLeft = tableWrapper.scrollLeft;
    });

    tableWrapper.addEventListener('mouseleave', () => {
        isDown = false;
        tableWrapper.classList.remove('active');
    });

    tableWrapper.addEventListener('mouseup', () => {
        isDown = false;
        tableWrapper.classList.remove('active');
    });

    tableWrapper.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - tableWrapper.offsetLeft;
        const walk = (x - startX) * 2; // Scroll-fast multiplier
        tableWrapper.scrollLeft = scrollLeft - walk;
    });

    // Untuk mobile touch event
    let startTouchX;
    tableWrapper.addEventListener('touchstart', (e) => {
        startTouchX = e.touches[0].pageX;
        scrollLeft = tableWrapper.scrollLeft;
    });

    tableWrapper.addEventListener('touchmove', (e) => {
        const touchX = e.touches[0].pageX;
        const walk = (touchX - startTouchX) * 2; // Scroll-fast multiplier
        tableWrapper.scrollLeft = scrollLeft - walk;
    });
</script>
@endsection

@push('page_css')
<style>
    #table-wrapper {
        overflow-x: auto;
    }

    /* Mencegah tabel melebar */
    table {
        width: 100%;
    }

    /* Menyelaraskan teks dan gambar */
    .table td, .table th {
        vertical-align: middle;
        white-space: nowrap;
    }

    /* Ukuran tetap untuk gambar */
    .img-fluid {
        max-width: 30px;
        height: auto;
    }
</style>

@endpush
