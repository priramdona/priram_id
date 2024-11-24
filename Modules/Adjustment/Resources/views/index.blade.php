@extends('layouts.app')

@section('title', __('adjustment.adjustments'))

@section('third_party_stylesheets')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('adjustment.home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('adjustment.adjustments') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('adjustments.create') }}" class="btn btn-primary">
                            {{ __('adjustment.add_adjustment') }} <i class="bi bi-plus"></i>
                        </a>

                        <hr>

                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">{{ __('user.delete_confirmation') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p id="deleteMessage">{{ __('user.confirm_delete', ['name' => '']) }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('user.cancel') }}</button>
                                        <form id="deleteForm" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">{{ __('user.delete') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    {!! $dataTable->scripts() !!}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
     document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('deleteModal');
        var deleteForm = document.getElementById('deleteForm');
        var deleteMessage = document.getElementById('deleteMessage');

        // Event listener ketika modal akan ditampilkan
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Tombol yang memicu modal
            var userId = button.getAttribute('data-id'); // Ambil ID dari atribut data-id
            var userName = button.getAttribute('data-name'); // Ambil nama dari atribut data-name

            // Set action URL untuk form delete
            var deleteUrl = "{{ route('adjustments.destroy', ':id') }}".replace(':id', userId);
            deleteForm.setAttribute('action', deleteUrl);

            // Set pesan konfirmasi
            deleteMessage.textContent = "{{ __('user.confirm_delete', ['name' => ':name']) }}".replace(':name', userName);
        });
    });
    </script>
@endpush
