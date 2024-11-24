@can('edit_customers')
    <a href="{{ route('customers.edit', $data->id) }}" class="btn btn-info btn-sm" title="{{ __('people.edit_customer') }}">
        <i class="bi bi-pencil"></i>
    </a>
@endcan
@can('show_customers')
    <a href="{{ route('customers.show', $data->id) }}" class="btn btn-primary btn-sm" title="{{ __('people.details') }}">
        <i class="bi bi-eye"></i>
    </a>
@endcan
@can('delete_customers')
    {{-- <button id="delete" class="btn btn-danger btn-sm" onclick="
        event.preventDefault();
        if (confirm('{{ __('people.delete_confirmation') }}')) {
            document.getElementById('destroy{{ $data->id }}').submit()
        }
        ">
        <i class="bi bi-trash"></i>
        <form id="destroy{{ $data->id }}" class="d-none" action="{{ route('customers.destroy', $data->id) }}" method="POST">
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
