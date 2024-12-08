@extends('layouts.app')

@section('title', __('menu.withdrawal_detail'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{  __('products.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{  __('menu.withdrawal') }}</a></li>
        <li class="breadcrumb-item active">{{  __('menu.withdrawal_detail') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">

            <div class="col-lg-9">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="product-details">
                            <div class="detail-item">

                                <span class="detail-label">{{ __('payment_gateway.date') }}</span>
                                <span class="detail-value">{{ $data->created_at }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('payment_gateway.status') }}</span>
                                <span class="detail-value">{{ $data->status }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('payment_gateway.channel_code') }}</span>
                                <span class="detail-value">{{ $data->transactional->channel_code }}</span>
                            </div>
                            @php
                                $channelProperties = json_decode($history->transactional->channel_properties, true);
                            @endphp
                            <div class="detail-item">
                                <span class="detail-label">{{ __('payment_gateway.account_name') }}</span>

                                <span class="detail-value">{{ $data->transactional->channel_properties['account_holder_name'] }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('payment_gateway.account_number') }}</span>
                                @if(is_array($channelProperties) && isset($channelProperties['account_number']))
                                    <span class="detail-value">{{ $channelProperties['account_number'] }}</span>
                                @else
                                    <span class="detail-value">{{ __('N/A') }}</span>
                                @endif
                                {{-- <span class="detail-value">{{ $data->transactional->channel_properties['account_number'] }}</span> --}}
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('payment_gateway.estimated_arrival') }}</span>
                                <span class="detail-value">{{ $data->transactional->estimated_arrival_time }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('payment_gateway.requested_amount') }}</span>
                                <span class="detail-value">{{ format_currency($data->amount)  }}</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">{{ __('payment_gateway.deduction_amount') }}</span>
                                <span class="detail-value">{{ format_currency($data->transaction_amount) }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('payment_gateway.receipt_notification') }} (%)</span>
                                <span class="detail-value">{{ $data->transactional->receipt_notification['email_to'][0] ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">{{ __('payment_gateway.description') }}</span>
                                <span class="detail-value">{{ $data->description ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection

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
