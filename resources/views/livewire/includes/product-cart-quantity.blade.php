{{-- <div class="input-group d-flex justify-content-center">
    <input  wire:change="quantityChange($event.target.value,'{{ $cart_item->rowId }}', '{{ $cart_item->id }}')" style="min-width: 40px;max-width: 90px;" type="text" class="form-control" value="{{ $cart_item->qty }}" min="1">
</div> --}}
    {{-- <input wire:model="quantity.{{ $cart_item->id }}" style="min-width: 40px;max-width: 90px;" type="number" class="form-control" value="{{ $cart_item->qty }}" min="1"> --}}
    {{-- <div class="input-group-append">
        <button type="button" wire:model="updateQuantity('{{ $cart_item->rowId }}', '{{ $cart_item->id }}')" class="btn btn-info">
            <i class="bi bi-check"></i>
        </button>
    </div> --}}
    <div class="input-group d-flex justify-content-center">
        <input
            id="quantity-input-{{ $cart_item->rowId }}"
            wire:change="quantityChange($event.target.value,'{{ $cart_item->rowId }}', '{{ $cart_item->id }}')"
            style="min-width: 40px; max-width: 90px;"
            type="text"
            class="form-control"
            value="{{ $cart_item->qty }}"
            min="1"
            onkeypress="return isNumberKey(event)"
            onchange="validateMinimum(this)"
            onkeydown="if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') { event.preventDefault(); }"
        >
    </div>
    @push('page_scripts')
        <script>
            Livewire.on('quantityUpdated', (rowId, newQuantity) => {
                // Update input value langsung di tampilan
                document.getElementById(`quantity-input-${rowId}`).value = newQuantity;
            });

            function isNumberKey(evt) {
                // Mencegah karakter non-numerik
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                // Izinkan hanya angka dan tombol backspace
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }
                return true;
            }
        </script>
@endpush


