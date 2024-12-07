@extends('layouts.app')

@section('title', __('purchase_return.title'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('purchase_return.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchase-returns.index') }}">{{ __('purchase_return.purchase_returns') }}</a></li>
        <li class="breadcrumb-item active">{{ __('purchase_return.details') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap align-items-center">
                        <div>
                            {{ __('purchase_return.reference') }}: <strong>{{ $purchase_return->reference }}</strong>
                        </div>
                        <a class="btn btn-sm btn-info mfs-auto mfe-1 d-print-none" onclick="fetchPdf('{{ $purchase_return->id }}')" >
                            <i class="bi bi-save"></i> {{ __('purchase.show.save') }}
                        </a>
                        {{-- <a target="_blank" class="btn btn-sm btn-secondary mfs-auto mfe-1 d-print-none" href="{{ route('purchase-returns.pdf', $purchase_return->id) }}">
                            <i class="bi bi-printer"></i> {{ __('purchase_return.print') }}
                        </a>
                        <a target="_blank" class="btn btn-sm btn-info mfe-1 d-print-none" href="{{ route('purchase-returns.pdf', $purchase_return->id) }}">
                            <i class="bi bi-save"></i> {{ __('purchase_return.save') }}
                        </a> --}}
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('purchase_return.company_info') }}:</h5>
                                <div><strong>{{ settings()->company_name }}</strong></div>
                                <div>{{ settings()->company_address }}</div>
                                <div>{{ __('purchase_return.email') }}: {{ settings()->company_email }}</div>
                                <div>{{ __('purchase_return.phone') }}: {{ settings()->company_phone }}</div>
                            </div>

                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('purchase_return.supplier_info') }}:</h5>
                                <div><strong>{{ $supplier->supplier_name }}</strong></div>
                                <div>{{ $supplier->address }}</div>
                                <div>{{ __('purchase_return.email') }}: {{ $supplier->supplier_email }}</div>
                                <div>{{ __('purchase_return.phone') }}: {{ $supplier->supplier_phone }}</div>
                            </div>

                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('purchase_return.invoice_info') }}:</h5>
                                <div>{{ __('purchase_return.invoice') }}: <strong>INV/{{ $purchase_return->reference }}</strong></div>
                                <div>{{ __('purchase_return.date') }}: {{ \Carbon\Carbon::parse($purchase_return->date)->format('d M, Y') }}</div>
                                <div>
                                    {{ __('purchase_return.status') }}: <strong>{{ $purchase_return->status }}</strong>
                                </div>
                                <div>
                                    {{ __('purchase_return.payment_status') }}: <strong>{{ $purchase_return->payment_status }}</strong>
                                </div>
                            </div>

                        </div>

                        <div class="table-responsive-sm">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="white-space: nowrap;" class="align-middle">{{ __('purchase_return.product') }}</th>
                                    <th style="white-space: nowrap;" class="align-middle">{{ __('purchase_return.net_unit_price') }}</th>
                                    <th style="white-space: nowrap;" class="align-middle">{{ __('purchase_return.quantity') }}</th>
                                    <th style="white-space: nowrap;" class="align-middle">{{ __('purchase_return.discount') }}</th>
                                    <th style="white-space: nowrap;" class="align-middle">{{ __('purchase_return.tax') }}</th>
                                    <th style="white-space: nowrap;" class="align-middle">{{ __('purchase_return.sub_total') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($purchase_return->purchaseReturnDetails as $item)
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
                                        <td style="white-space: nowrap;" class="left"><strong>{{ __('purchase_return.discount') }} ({{ $purchase_return->discount_percentage }}%)</strong></td>
                                        <td style="white-space: nowrap;" class="right">{{ format_currency($purchase_return->discount_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="white-space: nowrap;" class="left"><strong>{{ __('purchase_return.tax') }} ({{ $purchase_return->tax_percentage }}%)</strong></td>
                                        <td style="white-space: nowrap;" class="right">{{ format_currency($purchase_return->tax_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="white-space: nowrap;" class="left"><strong>{{ __('purchase_return.shipping') }}</strong></td>
                                        <td style="white-space: nowrap;" class="right">{{ format_currency($purchase_return->shipping_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="white-space: nowrap;" class="left"><strong>{{ __('purchase_return.grand_total') }}</strong></td>
                                        <td style="white-space: nowrap;" class="right"><strong>{{ format_currency($purchase_return->total_amount) }}</strong></td>
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
    const url = `/purchase-returns/pdf/${id}`; // Endpoint Laravel Anda

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
