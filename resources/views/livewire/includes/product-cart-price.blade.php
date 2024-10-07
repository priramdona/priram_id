<div class="input-group d-flex justify-content-left">

    <label for="price">Custom <i class="bi bi-question-circle-fill text-info" data-toggle="tooltip" data-placement="top" title="If you want change from the original price."></i> : </label>
    <input id='price' name='price' wire:change="priceChange($event.target.value,'{{ $cart_item->rowId }}', '{{ $cart_item->id }}')" style="min-width: 40px;max-width: 90px;" type="text" class="form-control"  value={{ ($cart_item->price) }} min="0">

    {{-- <input wire:model="unit_price.{{ $cart_item->id }}" style="min-width: 40px;max-width: 90px;" type="text" class="form-control" min="0">
    <div class="input-group-append">
        <button @click="open{{ $cart_item->id }} = !open{{ $cart_item->id }}" type="button" wire:click="updatePrice('{{ $cart_item->rowId }}', '{{ $cart_item->id }}')" class="btn btn-info">
            <i class="bi bi-check"></i>
        </button>
    </div> --}}
</div>
