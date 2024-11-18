<div class="btn-group dropleft">
    <button type="button" class="btn btn-ghost-primary dropdown rounded" data-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-three-dots-vertical"></i>
    </button>
    <div class="dropdown-menu">
        @can('access_purchase_return_payments')
            <a href="{{ route('purchase-return-payments.index', $data->id) }}" class="dropdown-item">
                <i class="bi bi-cash-coin mr-2 text-warning" style="line-height: 1;"></i> {{ __('purchase_return.show_payments') }}
            </a>
        @endcan
        @can('access_purchase_return_payments')
            @if($data->due_amount > 0)
                <a href="{{ route('purchase-return-payments.create', $data->id) }}" class="dropdown-item">
                    <i class="bi bi-plus-circle-dotted mr-2 text-success" style="line-height: 1;"></i> {{ __('purchase_return.add_payment') }}
                </a>
            @endif
        @endcan
        @can('edit_purchase_returns')
            <a href="{{ route('purchase-returns.edit', $data->id) }}" class="dropdown-item">
                <i class="bi bi-pencil mr-2 text-primary" style="line-height: 1;"></i> {{ __('purchase_return.edit') }}
            </a>
        @endcan
        @can('show_purchase_returns')
            <a href="{{ route('purchase-returns.show', $data->id) }}" class="dropdown-item">
                <i class="bi bi-eye mr-2 text-info" style="line-height: 1;"></i> {{ __('purchase_return.details') }}
            </a>
        @endcan
        @can('delete_purchase_return')
            <button id="delete" class="dropdown-item" onclick="
                event.preventDefault();
                if (confirm('{{ __('purchase_return.confirm_delete') }}')) {
                document.getElementById('destroy{{ $data->id }}').submit()
                }">
                <i class="bi bi-trash mr-2 text-danger" style="line-height: 1;"></i> {{ __('purchase_return.delete') }}
                <form id="destroy{{ $data->id }}" class="d-none" action="{{ route('purchase-returns.destroy', $data->id) }}" method="POST">
                    @csrf
                    @method('delete')
                </form>
            </button>
        @endcan
    </div>
</div>
