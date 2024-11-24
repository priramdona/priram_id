@can('access_sale_payments')
@if(blank($data->payment_channel_id))
    <a href="{{ route('sale-payments.edit', [$data->sale->id, $data->id]) }}" class="btn btn-info btn-sm">
        <i class="bi bi-pencil"></i>
    </a>
@else
    Online
@endif
@endcan
@can('access_sale_payments')
@if(blank($data->payment_channel_id))
    {{-- <button id="delete" class="btn btn-danger btn-sm" onclick="
        event.preventDefault();
        if (confirm('Are you sure? It will delete the data permanently!')) {
        document.getElementById('destroy{{ $data->id }}').submit()
        }
        ">
        <i class="bi bi-trash"></i>
        <form id="destroy{{ $data->id }}" class="d-none" action="{{ route('sale-payments.destroy', $data->id) }}" method="POST">
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
    @endif
@endcan
