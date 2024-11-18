@extends('layouts.app')

@section('title', __('sales_return.sales_details'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('sales_return.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('sale-returns.index') }}">{{ __('sales_return.sale_returns') }}</a></li>
        <li class="breadcrumb-item active">{{ __('sales_return.details') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap align-items-center">
                        <div>
                            {{ __('sales_return.reference') }}: <strong>{{ $sale_return->reference }}</strong>
                        </div>
                        <a target="_blank" class="btn btn-sm btn-secondary mfs-auto mfe-1 d-print-none" href="{{ route('sale-returns.pdf', $sale_return->id) }}">
                            <i class="bi bi-printer"></i> {{ __('sales_return.print') }}
                        </a>
                        <a target="_blank" class="btn btn-sm btn-info mfe-1 d-print-none" href="{{ route('sale-returns.pdf', $sale_return->id) }}">
                            <i class="bi bi-save"></i> {{ __('sales_return.save') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('sales_return.company_info') }}:</h5>
                                <div><strong>{{ settings()->company_name }}</strong></div>
                                <div>{{ settings()->company_address }}</div>
                                <div>{{ __('sales_return.email') }}: {{ settings()->company_email }}</div>
                                <div>{{ __('sales_return.phone') }}: {{ settings()->company_phone }}</div>
                            </div>
                            @if($customer)
                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('sales_return.customer_info') }}:</h5>
                                <div><strong>{{ $customer->customer_name }}</strong></div>
                                <div>{{ $customer->address }}</div>
                                <div>{{ __('sales_return.email') }}: {{ $customer->customer_email }}</div>
                                <div>{{ __('sales_return.phone') }}: {{ $customer->customer_phone }}</div>
                            </div>
                            @else
                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('sales_return.customer_info') }}:</h5>
                                <div><strong>{{ __('sales_return.not_registered') }}</strong></div>
                                <div>-</div>
                                <div>{{ __('sales_return.email') }}: -</div>
                                <div>{{ __('sales_return.phone') }}: -</div>
                            </div>
                            @endif
                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('sales_return.invoice_info') }}:</h5>
                                <div>{{ __('sales_return.invoice') }}: <strong>INV/{{ $sale_return->reference }}</strong></div>
                                <div>{{ __('sales_return.date') }}: {{ \Carbon\Carbon::parse($sale_return->date)->format('d M, Y') }}</div>
                                <div>
                                    {{ __('sales_return.status') }}: <strong>{{ $sale_return->status }}</strong>
                                </div>
                                <div>
                                    {{ __('sales_return.payment_status') }}: <strong>{{ $sale_return->payment_status }}</strong>
                                </div>
                            </div>

                        </div>

                        <div class="table-responsive-sm">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th class="align-middle">{{ __('sales_return.product') }}</th>
                                    <th class="align-middle">{{ __('sales_return.net_unit_price') }}</th>
                                    <th class="align-middle">{{ __('sales_return.quantity') }}</th>
                                    <th class="align-middle">{{ __('sales_return.discount') }}</th>
                                    <th class="align-middle">{{ __('sales_return.tax') }}</th>
                                    <th class="align-middle">{{ __('sales_return.sub_total') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sale_return->saleReturnDetails as $item)
                                    <tr>
                                        <td class="align-middle">
                                            {{ $item->product_name }} <br>
                                            <span class="badge badge-success">
                                                {{ $item->product_code }}
                                            </span>
                                        </td>

                                        <td class="align-middle">{{ format_currency($item->unit_price) }}</td>

                                        <td class="align-middle">
                                            {{ $item->quantity }}
                                        </td>

                                        <td class="align-middle">
                                            {{ format_currency($item->product_discount_amount) }}
                                        </td>

                                        <td class="align-middle">
                                            {{ format_currency($item->product_tax_amount) }}
                                        </td>

                                        <td class="align-middle">
                                            {{ format_currency($item->sub_total) }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-5 ml-md-auto">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td class="left"><strong>{{ __('sales_return.discount') }} ({{ $sale_return->discount_percentage }}%)</strong></td>
                                        <td class="right">{{ format_currency($sale_return->discount_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="left"><strong>{{ __('sales_return.tax') }} ({{ $sale_return->tax_percentage }}%)</strong></td>
                                        <td class="right">{{ format_currency($sale_return->tax_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="left"><strong>{{ __('sales_return.shipping') }}</strong></td>
                                        <td class="right">{{ format_currency($sale_return->shipping_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="left"><strong>{{ __('sales_return.grand_total') }}</strong></td>
                                        <td class="right"><strong>{{ format_currency($sale_return->total_amount) }}</strong></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

