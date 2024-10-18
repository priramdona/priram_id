@extends('layouts.app')

@section('title', 'Product Details')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('payment-gateways.index') }}">Products</a></li>
        <li class="breadcrumb-item active">Details</li>
    </ol>
@endsection

@section('content')
    {{-- <div class="container-fluid mb-4">
        <div class="row mb-3">
            <div class="col-md-12">
                <input type="text" class="form-control" name="result" value="{{ $result }}">
                <input type="text" class="form-control" name="result_details" value="{{ $resultDetails }}">

                {{-- {!! \Milon\Barcode\Facades\DNS1DFacade::getBarCodeSVG($product->product_code, $product->product_barcode_symbology, 2, 110) !!} --}}
            {{-- </div>
        </div>

    </div> --}}

    <div class="container">
        <h2 class="my-4">Payment Channels information</h2>

        <!-- Cek apakah ada hasil dari query -->

        @if($result->isNotEmpty())
            <!-- Wrapper for table to be scrollable via dragging -->
            <div class="table-responsive" id="table-wrapper">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Payment Fee</th>
                            <th>PPN (From Payment Fee)</th>
                            <th>Min Amount</th>
                            <th>Max Amount</th>
                            <th>Payment Process</th>
                            <th>Settlement</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Looping hasil dari $result -->
                        @foreach($result as $channel)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($channel->image_url)
                                            <img src="{{ $channel->image_url }}" alt="{{ $channel->name }} Image" class="img-fluid mr-2" style="max-width: 50px;">
                                        @else
                                            <span>No Image</span>
                                        @endif
                                        <span>{{ $channel->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $channel->type == 'VIRTUAL_ACCOUNT' ? 'VA' : 'EWallet' }}</td>
                                <td>
                                    @if($channel->fee_type_1 == "%")
                                        {{ round($channel->fee_value_1, 2) }} %
                                    @else
                                        {{ format_currency($channel->fee_value_1) }}
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
                                <td>{{ $channel->settlement }} Days</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No Payment Channels found.</p>
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
