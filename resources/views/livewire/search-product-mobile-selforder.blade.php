<div class="position-relative">
    <div class="card mb-0 border-0 shadow-sm">
        <div id="interactive" class="viewport">
            <video id="video" autoplay></video>
            <div class="scanner-laser"></div>
        </div>

        <div class="card-body">
            <div class="form-group mb-0">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="bi bi-search text-primary"></i>
                        </div>
                    </div>
                    <input id="productcode" wire:keydown.escape="resetQuery" wire:model.live.debounce.500ms="query" type="text" class="form-control" placeholder="Scan the Barcode or Type product name or code....">
                </div>
            </div>
        </div>
    </div>

    <div wire:loading class="card position-absolute mt-1 border-0" style="z-index: 1;left: 0;right: 0;">
        <div class="card-body shadow">
            <div class="d-flex justify-content-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($query))
        <div wire:click="resetQuery" class="position-fixed w-100 h-100" style="left: 0; top: 0; right: 0; bottom: 0;z-index: 1;"></div>
        @if($search_results->isNotEmpty())
            <div class="card position-absolute mt-1" style="z-index: 2;left: 0;right: 0;border: 0;">
                <div class="card-body shadow">
                    <ul class="list-group list-group-flush">
                        @foreach($search_results as $result)

                            <li class="list-group-item list-group-item-action">
                                <a wire:click="resetQuery" wire:click.prevent="selectProduct({{ $result }})" href="#">
                                    @forelse($result->getMedia('images') as $media)
                                        <img src="{{ $media->getUrl() }}" alt="{{ $result->product_name }}" border="0" width="50" class="img-thumbnail" align="center">
                                    @empty
                                        <img src="{{ $result->getFirstMediaUrl('images') }}" alt="Product Image" border="0" width="50" class="img-thumbnail" align="center">
                                    @endforelse
                                    {{ $result->product_name }} | {{ $result->product_code }}
                                </a>
                            </li>
                        @endforeach
                        @if($search_results->count() >= $how_many)
                             <li class="list-group-item list-group-item-action text-center">
                                 <a wire:click.prevent="loadMore" class="btn btn-primary btn-sm" href="#">
                                     Load More <i class="bi bi-arrow-down-circle"></i>
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
                        No Product Found....
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi suara beep
        var beepSound = new Audio("{{ asset('sounds/beep.mp3') }}");
        var klikSound = new Audio("{{ asset('sounds/klik.mp3') }}");

        Quagga.init({
                inputStream : {
                    name : "Live",
                    type : "LiveStream",
                    target: document.querySelector('#interactive'), // Elemen video
                    constraints: {
                        facingMode: "environment", // Menggunakan kamera belakang
                        advanced: [
                            { focusMode: "manual" }, // Nonaktifkan autofokus
                            { zoom: 4 },  // Perbesar tampilan untuk barcode kecil
                        ]
                    }
                },
                locator: {
                    patchSize: "small",  // Ukuran deteksi lebih kecil untuk barcode kecil
                    halfSample: false,    // Tidak perlu sampling 50% untuk akurasi yang lebih baik
                    debug: {
                        showCanvas: true, // Menampilkan canvas untuk debug
                        showPatches: true,
                        showFoundPatches: true,
                        showSkeleton: true,
                        showLabels: true,
                        showPatchLabels: true,
                        showRemainingPatchLabels: true,
                    }
                },
                area: { // Fokus deteksi hanya pada bagian tengah
                    top: "30%",    // 30% dari atas
                    right: "30%",  // 30% dari kanan
                    left: "30%",   // 30% dari kiri
                    bottom: "30%"  // 30% dari bawah
                },
                decoder : {
                    readers : ["code_128_reader", "ean_reader"],  // Jenis barcode yang ingin di-scan
                },
                locate: true // Aktifkan mode pelacakan barcode
            }, function(err) {
                if (err) {
                    console.log(err);
                    return;
                }
                console.log("Quagga initialized successfully");
                Quagga.start();
            });
        // Event handler ketika barcode terdeteksi
        Quagga.onDetected(function(result) {
            var code = result.codeResult.code;
            let inputField = document.getElementById('productcode');
            if(inputField) {
                inputField.value = code;
                inputField.dispatchEvent(new Event('input'));

                // Mainkan suara beep setelah barcode terdeteksi
                klikSound.play();
            }
        });

        // Event handler ketika produk dipilih dari list
        document.querySelectorAll('a[wire\\:click]').forEach(function(element) {
            element.addEventListener('click', function() {
                // Mainkan suara beep setelah produk dipilih dari hasil pencarian
                beepSound.play();
            });
        });
        // Dengarkan event 'playBeep' dari Livewire
        window.addEventListener('playBeep', function () {
                beepSound.play(); // Memutar suara beep
            });

    });
</script>
