@extends('layouts.app')

@section('title', __('purchase.show.title'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('purchase.breadcrumb.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">{{ __('purchase.breadcrumb.purchases') }}</a></li>
        <li class="breadcrumb-item active">{{ __('purchase.show.details') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap align-items-center">
                        <div>
                            {{ __('purchase.show.reference') }}: <strong>{{ $purchase->reference }}</strong>
                        </div>
                        <a target="_blank" class="btn btn-sm btn-info mfs-auto mfe-1 d-print-none" onclick="fetchPdf('{{ $purchase->id }}')" >
                            <i class="bi bi-save"></i> {{ __('purchase.show.save') }}
                        </a>
                        {{-- <a target="_blank" class="btn btn-sm btn-secondary mfs-auto mfe-1 d-print-none" href="{{ route('purchases.pdf', $purchase->id) }}">
                            <i class="bi bi-printer"></i> {{ __('purchase.show.print') }}
                        </a>
                        <a target="_blank" class="btn btn-sm btn-info mfe-1 d-print-none" href="{{ route('purchases.pdf', $purchase->id) }}">
                            <i class="bi bi-save"></i> {{ __('purchase.show.save') }}
                        </a> --}}
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('purchase.show.company_info') }}:</h5>
                                <div><strong>{{ settings()->company_name }}</strong></div>
                                <div>{{ settings()->company_address }}</div>
                                <div>{{ __('purchase.show.email') }}: {{ settings()->company_email }}</div>
                                <div>{{ __('purchase.show.phone') }}: {{ settings()->company_phone }}</div>
                            </div>

                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('purchase.show.supplier_info') }}:</h5>
                                <div><strong>{{ $supplier->supplier_name }}</strong></div>
                                <div>{{ $supplier->address }}</div>
                                <div>{{ __('purchase.show.email') }}: {{ $supplier->supplier_email }}</div>
                                <div>{{ __('purchase.show.phone') }}: {{ $supplier->supplier_phone }}</div>
                            </div>

                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('purchase.show.invoice_info') }}:</h5>
                                <div>{{ __('purchase.show.invoice') }}: <strong>INV/{{ $purchase->reference }}</strong></div>
                                <div>{{ __('purchase.show.date') }}: {{ \Carbon\Carbon::parse($purchase->date)->format('d M, Y') }}</div>
                                <div>{{ __('purchase.show.status') }}: <strong>{{ $purchase->status }}</strong></div>
                                <div>{{ __('purchase.show.payment_status') }}: <strong>{{ $purchase->payment_status }}</strong></div>
                            </div>
                        </div>

                        <div class="table-responsive-sm">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="white-space: nowrap;" class="align-middle">{{ __('purchase.show.table.product') }}</th>
                                    <th style="white-space: nowrap;" class="align-middle">{{ __('purchase.show.table.net_unit_price') }}</th>
                                    <th style="white-space: nowrap;" class="align-middle">{{ __('purchase.show.table.quantity') }}</th>
                                    <th style="white-space: nowrap;" class="align-middle">{{ __('purchase.show.table.discount') }}</th>
                                    <th style="white-space: nowrap;" class="align-middle">{{ __('purchase.show.table.tax') }}</th>
                                    <th style="white-space: nowrap;" class="align-middle">{{ __('purchase.show.table.sub_total') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($purchase->purchaseDetails as $item)
                                    <tr>
                                        <td style="white-space: nowrap;" class="align-middle">
                                            {{ $item->product_name }} <br>
                                            <span class="badge badge-success">
                                                {{ $item->product_code }}
                                            </span>
                                        </td>
                                        <td style="white-space: nowrap;" class="align-middle">{{ format_currency($item->unit_price) }}</td>
                                        <td style="white-space: nowrap;" class="align-middle">{{ $item->quantity }}</td>
                                        <td style="white-space: nowrap;" class="align-middle">{{ format_currency($item->product_discount_amount) }}</td>
                                        <td style="white-space: nowrap;" class="align-middle">{{ format_currency($item->product_tax_amount) }}</td>
                                        <td style="white-space: nowrap;" class="align-middle">{{ format_currency($item->sub_total) }}</td>
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
                                        <td style="white-space: nowrap;" class="left"><strong>{{ __('purchase.show.discount') }} ({{ $purchase->discount_percentage }}%)</strong></td>
                                        <td style="white-space: nowrap;" class="right">{{ format_currency($purchase->discount_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="white-space: nowrap;" class="left"><strong>{{ __('purchase.show.tax') }} ({{ $purchase->tax_percentage }}%)</strong></td>
                                        <td style="white-space: nowrap;" class="right">{{ format_currency($purchase->tax_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="white-space: nowrap;" class="left"><strong>{{ __('purchase.show.shipping') }}</strong></td>
                                        <td style="white-space: nowrap;" class="right">{{ format_currency($purchase->shipping_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="white-space: nowrap;" class="left"><strong>{{ __('purchase.show.grand_total') }}</strong></td>
                                        <td style="white-space: nowrap;" class="right"><strong>{{ format_currency($purchase->total_amount) }}</strong></td>
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


@push('page_scripts')
<script>

    function fetchPdf(id) {
    const url = `/purchases/pdf/${id}`; // Endpoint Laravel Anda

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
