@extends('layouts.app')

@section('title', __('adjustment.adjustment_details'))

@push('page_css')
    @livewireStyles
@endpush

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('adjustment.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('adjustments.index') }}">{{ __('adjustment.adjustments') }}</a></li>
        <li class="breadcrumb-item active">{{ __('adjustment.details') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="2">
                                        {{ __('adjustment.date') }}
                                    </th>
                                    <th colspan="2">
                                        {{ __('adjustment.reference') }}
                                    </th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        {{ $adjustment->date }}
                                    </td>
                                    <td colspan="2">
                                        {{ $adjustment->reference }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>{{ __('adjustment.product_name') }}</th>
                                    <th>{{ __('adjustment.code') }}</th>
                                    <th>{{ __('adjustment.quantity') }}</th>
                                    <th>{{ __('adjustment.type') }}</th>
                                </tr>

                                @foreach($adjustment->adjustedProducts as $adjustedProduct)
                                    <tr>
                                        <td>{{ $adjustedProduct->product->product_name }}</td>
                                        <td>{{ $adjustedProduct->product->product_code }}</td>
                                        <td>{{ $adjustedProduct->quantity }}</td>
                                        <td>
                                            @if($adjustedProduct->type == 'add')
                                                (+) {{ __('adjustment.addition') }}
                                            @else
                                                (-) {{ __('adjustment.subtraction') }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
