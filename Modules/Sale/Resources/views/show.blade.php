@extends('layouts.app')

@section('title', __('sales.show.title'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('sales.breadcrumb.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">{{ __('sales.breadcrumb.sales') }}</a></li>
        <li class="breadcrumb-item active">{{ __('sales.breadcrumb.details') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap align-items-center">
                        <div>
                            {{ __('sales.show.reference') }}: <strong>{{ $sale->reference }}</strong>
                        </div>
                        <a target="_blank"  class="btn btn-sm btn-info mfs-auto mfe-1 d-print-none"  onclick="fetchPdf('{{ $sale->id }}')">
                            <i class="bi bi-save"></i> {{ __('sales.show.save') }}
                        </a>
                        {{-- <a target="_blank" class="btn btn-sm btn-info mfe-1 d-print-none" onclick="fetchPdf('{{ $sale->id }}')">

                        </a> --}}
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('sales.show.info.company') }}</h5>
                                <div><strong>{{ settings()->company_name }}</strong></div>
                                <div>{{ settings()->company_address }}</div>
                                <div>{{ __('sales.show.customer.email') }}: {{ settings()->company_email }}</div>
                                <div>{{ __('sales.show.customer.phone') }}: {{ settings()->company_phone }}</div>
                            </div>
                            @if($customer)
                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('sales.show.info.customer') }}</h5>
                                <div><strong>{{ $customer->customer_name }}</strong></div>
                                <div>{{ $customer->address }}</div>
                                <div>{{ __('sales.show.customer.email') }}: {{ $customer->customer_email }}</div>
                                <div>{{ __('sales.show.customer.phone') }}: {{ $customer->customer_phone }}</div>
                            </div>
                            @else
                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('sales.show.info.customer') }}</h5>
                                <div><strong>{{ __('sales.show.customer.not_registered') }}</strong></div>
                                <div>-</div>
                                <div>{{ __('sales.show.customer.email') }}: -</div>
                                <div>{{ __('sales.show.customer.phone') }}: -</div>
                            </div>
                            @endif
                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('sales.show.info.invoice') }}</h5>
                                <div>{{ __('sales.show.invoice.number') }}: <strong>INV/{{ $sale->reference }}</strong></div>
                                <div>{{ __('sales.show.invoice.date') }}: {{ \Carbon\Carbon::parse($sale->date)->format('d M, Y') }}</div>
                                <div>
                                    {{ __('sales.show.invoice.status') }}: <strong>{{ $sale->status }}</strong>
                                </div>
                                <div>
                                    {{ __('sales.show.invoice.payment_status') }}: <strong>{{ $sale->payment_status }}</strong>
                                </div>
                            </div>

                        </div>
                        <div class="table-responsive-sm">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th style="white-space: nowrap; font-size: 12px;" class="align-middle">{{ __("sales.show.table.product") }}</th>
                                    <th style="white-space: nowrap; font-size: 12px;" class="align-middle">{{ __("sales.show.table.net_unit_price") }}</th>
                                    <th style="white-space: nowrap; font-size: 12px;" class="align-middle">{{ __("sales.show.table.quantity") }}</th>
                                    <th style="white-space: nowrap; font-size: 12px;" class="align-middle">{{ __("sales.show.table.discount") }}</th>
                                    <th style="white-space: nowrap; font-size: 12px;" class="align-middle">{{ __("sales.show.table.tax") }}</th>
                                    <th style="white-space: nowrap; font-size: 12px;" class="align-middle">{{ __("sales.show.table.sub_total") }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sale->saleDetails as $item)
                                    <tr>
                                        <td style="white-space: nowrap; font-size: 12px;" class="align-middle">
                                            {{ $item->product_name }} <br>
                                            <span class="badge badge-success">
                                                {{ $item->product_code }}
                                            </span>
                                        </td>

                                        <td style="white-space: nowrap; font-size: 12px;" class="align-middle">{{ format_currency($item->unit_price) }}</td>

                                        <td style="white-space: nowrap; font-size: 12px;" class="align-middle">
                                            {{ $item->quantity }}
                                        </td>

                                        <td style="white-space: nowrap; font-size: 12px;" class="align-middle">
                                            {{ format_currency($item->product_discount_amount) }}
                                        </td>

                                        <td style="white-space: nowrap; font-size: 12px;" class="align-middle">
                                            {{ format_currency($item->product_tax_amount) }}
                                        </td>

                                        <td style="white-space: nowrap; font-size: 12px;" class="align-middle">
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
                                    @if ($sale->discount_amount > 0)
                                    <tr>
                                        <td style="white-space: nowrap; font-size: 12px;" class="left"><strong>{{ __("sales.show.table.discount") }} ({{ $sale->discount_percentage }}%)</strong></td>
                                        <td style="white-space: nowrap; font-size: 12px;" class="right">{{ format_currency($sale->discount_amount) }}</td>
                                    </tr>
                                    @endif
                                    @if ($sale->discount_amount > 0)
                                    <tr>
                                        <td style="white-space: nowrap; font-size: 12px;" class="left"><strong>{{ __("sales.show.table.tax") }} ({{ $sale->tax_percentage }}%)</strong></td>
                                        <td style="white-space: nowrap; font-size: 12px;" class="right">{{ format_currency($sale->tax_amount) }}</td>
                                    </tr>
                                    @endif
                                    @if ($sale->discount_amount > 0)
                                    <tr>
                                        <td style="white-space: nowrap; font-size: 12px;" class="left"><strong>{{ __("sales.show.table.shipping") }}</strong></td>
                                        <td style="white-space: nowrap; font-size: 12px;" class="right">{{ format_currency($sale->shipping_amount) }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td style="white-space: nowrap; font-size: 12px;" class="left"><strong>{{ __("sales.show.table.total") }}</strong></td>
                                        <td style="white-space: nowrap; font-size: 12px;" class="right"><strong>{{ format_currency($sale->total_amount) }}</strong></td>
                                    </tr>
                                    {{-- @if ($sale->additional_paid_amount > 0) --}}
                                    <tr>
                                        <td style="white-space: nowrap; font-size: 12px;" class="left">{{ __("sales.show.table.additional_amount") }}</td>
                                        <td style="white-space: nowrap; font-size: 12px;" class="right">{{ format_currency($sale->additional_paid_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="white-space: nowrap; font-size: 12px;" class="left"><strong>{{ __("sales.show.table.grand_total") }}</strong></td>
                                        <td style="white-space: nowrap; font-size: 12px;" class="right"><strong>{{ format_currency($sale->total_paid_amount) }}</strong></td>
                                    </tr>
                                    {{-- @endif --}}
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


@push('page_scripts')
<script>

    function fetchPdf(saleId) {
    const url = `/sales/pdf/${saleId}`; // Endpoint Laravel Anda

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.action === 'download_pdf') {
                if (window.AndroidInterface) {
                    window.AndroidInterface.sendPdfUrl(data.pdf_url);
                } else {
                    window.print();
                }
            }
        })
        .catch(error => {
            console.error('Error fetching PDF:', error);
        });
}
</script>
@endpush
