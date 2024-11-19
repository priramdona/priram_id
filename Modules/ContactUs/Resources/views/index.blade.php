@extends('layouts.app')

@section('content')

<div class="container mt-5">

    <div class="card mx-auto mt-4 p-4">
    <h1 class="text-center">{{ __('contacts.contact_us') }}</h1>

    {{-- Form Hubungi Kami --}}
    <form id="actionform" name="actionform" action="{{ route('contacts.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="form-group">
            <label for="category">{{ __('contacts.category') }}</label>
            <select id="category" name="category" class="form-control" required>
                <option value="">{{ __('contacts.select_category') }}</option>
                <option value="Complaint">{{ __('contacts.complaint') }}</option>
                <option value="Feedback">{{ __('contacts.feedback') }}</option>
                <option value="Inquiry">{{ __('contacts.inquiry') }}</option>
            </select>
        </div>
        <div class="form-group">
            <label for="name">{{ __('contacts.name') }}</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">{{ __('contacts.email') }}</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="message">{{ __('contacts.message') }}</label>
            <textarea id="message" name="message" rows="4" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('contacts.send') }}</button>
    </form>

    {{-- Riwayat Kontak --}}
    <h2>{{ __('contacts.history') }}</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ __('contacts.category') }}</th>
                <th>{{ __('contacts.name') }}</th>
                <th>{{ __('contacts.status') }}</th>
                <th>{{ __('contacts.date') }}</th>
                <th>{{ __('contacts.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contacts as $contact)
                <tr>
                    <td>{{ $contact->category }}</td>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->status }}</td>
                    <td>{{ $contact->created_at->format('d-m-Y') }}</td>
                    <td><a href="{{ route('contacts.show', $contact) }}" class="btn btn-info">{{ __('contacts.view') }}</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
@endsection
@push('page_scripts')
<script>

$('#actionform').on('submit', function (e) {

e.preventDefault();

var formData = $(this).serialize();
isSubmitting = true; // Set flag sebagai true untuk submit pertama kali

$.ajax({
    url: $(this).attr('action'),
    method: $(this).attr('method'),
    data: formData,
    success: function (response) {
        Swal.fire({
    title: 'Save and Sent!',
    text: 'Request Saved and Email Sent, our Staff will follow up max 1x24 Hour.',
    icon: 'success',
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
            $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                    },
    }).then((result) => {
            if (result.isConfirmed) {
                    window.location.href = '/home';
                }
            });
        },
});
});
</script>
@endpush

