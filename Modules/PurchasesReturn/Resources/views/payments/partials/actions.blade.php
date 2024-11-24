@can('access_purchase_return_payments')
    <a href="{{ route('purchase-return-payments.edit', [$data->purchaseReturn->id, $data->id]) }}" class="btn btn-info btn-sm">
        <i class="bi bi-pencil"></i>
    </a>
@endcan
@can('access_purchase_return_payments')
    {{-- <button id="delete" class="btn btn-danger btn-sm" onclick="
        event.preventDefault();
        if (confirm('{{ __('purchase_return.confirm_delete') }}')) {
        document.getElementById('destroy{{ $data->id }}').submit()
        }
        ">
        <i class="bi bi-trash"></i>
        <form id="destroy{{ $data->id }}" class="d-none" action="{{ route('purchase-return-payments.destroy', $data->id) }}" method="POST">
            @csrf
            @method('delete')
        </form>
    </button> --}}
    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
        data-id="{{ $data->id }}"
        data-name="{{ $data->name }}"
        title="{{ __('user.delete_user') }}">
        <i class="bi bi-trash"></i>
    </button>
@endcan
