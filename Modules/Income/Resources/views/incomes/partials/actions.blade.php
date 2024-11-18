@can('edit_incomes')
@if($data->incomePayments->payment_channel_id == null)
<a href="{{ route('incomes.edit', $data->id) }}" class="btn btn-info btn-sm" title="{{ __('income.edit') }}">
    <i class="bi bi-pencil"></i>
</a>
@else
<a class="btn btn-danger btn-sm" title="{{ __('income.action_disabled_payment_online') }}">
    <i class="bi bi-x-circle"></i>
</a>
@endif
@endcan
@can('delete_incomes')
@if($data->incomePayments->payment_channel_id == null)
<button id="delete" class="btn btn-danger btn-sm" onclick="
    event.preventDefault();
    if (confirm('{{ __('income.confirm_delete') }}')) {
    document.getElementById('destroy{{ $data->id }}').submit();
    }
    ">
    <i class="bi bi-trash"></i>
    <form id="destroy{{ $data->id }}" class="d-none" action="{{ route('incomes.destroy', $data->id) }}" method="POST">
        @csrf
        @method('delete')
    </form>
</button>
@else
<a class="btn btn-danger btn-sm" title="{{ __('income.action_disabled_payment_online') }}">
    <i class="bi bi-x-circle"></i>
</a>
@endif
@endcan
