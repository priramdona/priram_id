<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-cart-plus"></i> {{ __('sales.search_product.product_list') }}</h5>
        <button class="btn btn-link" type="button" id="expandlist">
            <i class="bi bi-caret-down-fill" id='iconexpand'></i>
        </button>
    </div>
    <div  wire:ignore.self id="productList" hidden>
        <div class="card-body">
            <livewire:pos.filter :categories="$categories"/>
            <div class="row position-relative">
                <div wire:loading.flex class="col-12 position-absolute justify-content-center align-items-center" style="top:0;right:0;left:0;bottom:0;background-color: rgba(255,255,255,0.5);z-index: 99;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">{{ __('sales.search_product.loading') }}</span>
                    </div>
                </div>

                @forelse($products as $product)
                    <div wire:click.prevent="selectProduct({{ $product }})" class="col-6 col-md-6 col-lg-4 mb-3" style="cursor: pointer;">
                        <div class="card border-0 shadow h-100">
                            <div class="position-relative">
                                <img src="{{ $product->image_url }}" class="card-img-top" alt="Product Image" style="width: 100%; height: 100%; object-fit: contain;">
                               <div class="badge badge-info mb-3 position-absolute" style="left:10px;top: 10px;">{{ __('sales.search_product.stock') }}: {{ $product->product_quantity }}</div>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <h6 style="font-size: 13px;" class="card-title mb-0">{{ $product->product_name }}</h6>
                                    <span class="badge badge-success">
                                        {{ $product->product_code }}
                                    </span>
                                </div>
                                <p class="card-text font-weight-bold">{{ format_currency($product->product_price) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning mb-0">
                            {{ __('sales.search_product.no_product') }}
                        </div>
                    </div>
                @endforelse
            </div>
            <div @class(['mt-3' => $products->hasPages()])>
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
{{--
<div>
    <div class="card border-0 shadow-sm mt-3">

    </div>
</div> --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var beepSound = new Audio("{{ asset('sounds/beep.mp3') }}");

        // Dengarkan event 'playBeep' dari Livewire
        window.addEventListener('playBeep', function () {
                beepSound.play(); // Memutar suara beep
            });

    });


    $(document).on('click', '#expandlist', function() {
        // $('#action_account_barcode').attr('hidden', false);

        var div = $('#productList');
        var icon = $('#iconexpand');

        // Cek apakah div tersembunyi menggunakan atribut hidden
        if (div.attr('hidden')) {
            icon.removeClass('bi-caret-down-fill').addClass('bi-caret-up-fill');
            div.removeAttr('hidden');
        } else {
            icon.removeClass('bi-caret-up-fill').addClass('bi-caret-down-fill');
            div.attr('hidden', true);
        }


    });
      // Initialize toolti
    //   $('#expand-tooltip').tooltip();


        // // Initialize tooltip
        // $('#expand-tooltip').tooltip();

        // // Change tooltip text when the collapse state changes
        // $('#productList').on('shown.bs.collapse', function () {
        //     $('#expand-tooltip').attr('title', 'Collapse product list').tooltip('update');
        // });

        // $('#productList').on('hidden.bs.collapse', function () {
        //     $('#expand-tooltip').attr('title', 'Expand product list').tooltip('update');
        // });

</script>
