@extends('layouts.app')

@section('title', __('quotation.show.title'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('quotation.breadcrumb.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}">{{ __('quotation.breadcrumb.quotations') }}</a></li>
        <li class="breadcrumb-item active">{{ __('quotation.show.details') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap align-items-center">
                        <div>
                            {{ __('quotation.show.reference') }}: <strong>{{ $quotation->reference }}</strong>
                        </div>
                        <a target="_blank" class="btn btn-sm btn-secondary mfs-auto mfe-1 d-print-none" href="{{ route('quotations.pdf', $quotation->id) }}">
                            <i class="bi bi-printer"></i> {{ __('quotation.show.print') }}
                        </a>
                        <a target="_blank" class="btn btn-sm btn-info mfe-1 d-print-none" href="{{ route('quotations.pdf', $quotation->id) }}">
                            <i class="bi bi-save"></i> {{ __('quotation.show.save') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('quotation.show.company_info') }}:</h5>
                                <div><strong>{{ settings()->company_name }}</strong></div>
                                <div>{{ settings()->company_address }}</div>
                                <div>{{ __('quotation.show.email') }}: {{ settings()->company_email }}</div>
                                <div>{{ __('quotation.show.phone') }}: {{ settings()->company_phone }}</div>
                            </div>

                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('quotation.show.customer_info') }}:</h5>
                                <div><strong>{{ $customer->customer_name }}</strong></div>
                                <div>{{ $customer->address }}</div>
                                <div>{{ __('quotation.show.email') }}: {{ $customer->customer_email }}</div>
                                <div>{{ __('quotation.show.phone') }}: {{ $customer->customer_phone }}</div>
                            </div>

                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('quotation.show.invoice_info') }}:</h5>
                                <div>{{ __('quotation.show.invoice') }}: <strong>INV/{{ $quotation->reference }}</strong></div>
                                <div>{{ __('quotation.show.date') }}: {{ \Carbon\Carbon::parse($quotation->date)->format('d M, Y') }}</div>
                                <div>{{ __('quotation.show.status') }}: <strong>{{ $quotation->status }}</strong></div>
                                <div>{{ __('quotation.show.payment_status') }}: <strong>{{ $quotation->payment_status }}</strong></div>
                            </div>
                        </div>

                        <div class="table-responsive-sm">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th class="align-middle">{{ __('quotation.show.table.product') }}</th>
                                    <th class="align-middle">{{ __('quotation.show.table.net_unit_price') }}</th>
                                    <th class="align-middle">{{ __('quotation.show.table.quantity') }}</th>
                                    <th class="align-middle">{{ __('quotation.show.table.discount') }}</th>
                                    <th class="align-middle">{{ __('quotation.show.table.tax') }}</th>
                                    <th class="align-middle">{{ __('quotation.show.table.sub_total') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($quotation->quotationDetails as $item)
                                    <tr>
                                        <td class="align-middle">
                                            {{ $item->product_name }} <br>
                                            <span class="badge badge-success">
                                                {{ $item->product_code }}
                                            </span>
                                        </td>
                                        <td class="align-middle">{{ format_currency($item->unit_price) }}</td>
                                        <td class="align-middle">{{ $item->quantity }}</td>
                                        <td class="align-middle">{{ format_currency($item->product_discount_amount) }}</td>
                                        <td class="align-middle">{{ format_currency($item->product_tax_amount) }}</td>
                                        <td class="align-middle">{{ format_currency($item->sub_total) }}</td>
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
                                        <td class="left"><strong>{{ __('quotation.show.discount') }} ({{ $quotation->discount_percentage }}%)</strong></td>
                                        <td class="right">{{ format_currency($quotation->discount_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="left"><strong>{{ __('quotation.show.tax') }} ({{ $quotation->tax_percentage }}%)</strong></td>
                                        <td class="right">{{ format_currency($quotation->tax_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="left"><strong>{{ __('quotation.show.shipping') }}</strong></td>
                                        <td class="right">{{ format_currency($quotation->shipping_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="left"><strong>{{ __('quotation.show.grand_total') }}</strong></td>
                                        <td class="right"><strong>{{ format_currency($quotation->total_amount) }}</strong></td>
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

