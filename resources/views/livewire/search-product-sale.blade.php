
<div class="position-relative">
    <div class="card mb-0 border-0 shadow-sm">
        <div class="card-body">
            <div class="form-group mb-0">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="bi bi-search text-primary"></i>
                        </div>
                    </div>
                    <input
                        id="productcode"
                        wire:keydown.escape="resetQuery"
                        wire:model.live.debounce.500ms="query"
                        type="text"
                        class="form-control"
                        placeholder="{{ __('sales.search_product_sale.placeholder') }}"
                    >
                </div>
            </div>
        </div>
    </div>

    <div wire:loading class="card position-absolute mt-1 border-0" style="z-index: 1;left: 0;right: 0;">
        <div class="card-body shadow">
            <div class="d-flex justify-content-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">{{ __('sales.search_product_sale.loading') }}</span>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($query))
        <div wire:click="resetQuery" class="position-fixed w-100 h-100" style="left: 0; top: 0; right: 0; bottom: 0;z-index: 1;"></div>
        @if($search_results->isNotEmpty())
            <div class="card position-absolute mt-1" style="z-index: 1050;left: 0;right: 0;border: 0;">
                <div class="card-body shadow">
                    <ul class="list-group list-group-flush">
                        @foreach($search_results as $result)
                            <li class="list-group-item list-group-item-action">
                                <a wire:click="resetQuery" wire:click.prevent="selectProduct({{ $result }})" href="#">
                                        <img src="{{ $result->image_url }}" alt="Product Image" border="0" width="50" class="img-thumbnail" align="center">
                                    {{ $result->product_name }} | {{ $result->product_code }}
                                </a>
                            </li>
                        @endforeach
                        @if($search_results->count() >= $how_many)
                             <li class="list-group-item list-group-item-action text-center">
                                 <a wire:click.prevent="loadMore" class="btn btn-primary btn-sm" href="#">
                                     {{ __('sales.search_product_sale.load_more') }} <i class="bi bi-arrow-down-circle"></i>
                                 </a>
                             </li>
                        @endif
                    </ul>
                </div>
            </div>
        @else
            <div class="card position-absolute mt-1 border-0" style="z-index: 1;left: 0;right: 0;">
                <div class="card-body shadow">
                    <div class="alert alert-warning mb-0">
                        {{ __('sales.search_product_sale.no_product') }}
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>

<script>
    $(document).on('click', '#expandcamera', function() {
        // $('#action_account_barcode').attr('hidden', false);

        var div = $('#cameraview');
        var icon = $('#iconexpandcamera');

        // Cek apakah div tersembunyi menggunakan atribut hidden
        if (div.attr('hidden')) {
            icon.removeClass('bi-caret-down-fill').addClass('bi-caret-up-fill');
            div.removeAttr('hidden');
        } else {
            icon.removeClass('bi-caret-up-fill').addClass('bi-caret-down-fill');
            div.attr('hidden', true);
        }


    });


    $('#expand-tooltip').tooltip();
    document.addEventListener('DOMContentLoaded', function () {
        var beepSound = new Audio("{{ asset('sounds/beep.mp3') }}");
        var klikSound = new Audio("{{ asset('sounds/klik.mp3') }}");

        Quagga.init({
                inputStream : {
                    name : "Live",
                    type : "LiveStream",
                    target: document.querySelector('#interactive'),
                    constraints: {
                        facingMode: "environment",
                        advanced: [
                            { focusMode: "manual" },
                            { zoom: 4 },
                        ]
                    }
                },
                locator: {
                    patchSize: "small",
                    halfSample: false,
                    debug: {
                        showCanvas: true,
                        showPatches: true,
                        showFoundPatches: true,
                        showSkeleton: true,
                        showLabels: true,
                        showPatchLabels: true,
                        showRemainingPatchLabels: true,
                    }
                },
                area: {
                    top: "30%",
                    right: "30%",
                    left: "30%",
                    bottom: "30%"
                },
                decoder : {
                    readers : ["code_128_reader", "ean_reader"],
                },
                locate: true
            }, function(err) {
                if (err) {
                    return;
                }
                Quagga.start();
            });

        Quagga.onDetected(function(result) {
            var code = result.codeResult.code;
            let inputField = document.getElementById('productcode');
            if(inputField) {
                inputField.value = code;
                inputField.dispatchEvent(new Event('input'));
                klikSound.play();
            }
        });

        document.querySelectorAll('a[wire\\:click]').forEach(function(element) {
            element.addEventListener('click', function() {
                beepSound.play();
            });
        });

        window.addEventListener('playBeep', function () {
                beepSound.play();
            });
    });
</script>
