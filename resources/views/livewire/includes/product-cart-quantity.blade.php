<div class="input-group d-flex justify-content-center">
    <input  wire:change="quantityChange($event.target.value,'{{ $cart_item->rowId }}', '{{ $cart_item->id }}')" style="min-width: 40px;max-width: 90px;" type="number" class="form-control" value="{{ $cart_item->qty }}" min="1">

    {{-- <input wire:model="quantity.{{ $cart_item->id }}" style="min-width: 40px;max-width: 90px;" type="number" class="form-control" value="{{ $cart_item->qty }}" min="1"> --}}
    {{-- <div class="input-group-append">
        <button type="button" wire:model="updateQuantity('{{ $cart_item->rowId }}', '{{ $cart_item->id }}')" class="btn btn-info">
            <i class="bi bi-check"></i>
        </button>
    </div> --}}
</div>

