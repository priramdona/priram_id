@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('contacts.contact_detail') }}</h1>

    <ul>
        <li><strong>{{ __('contacts.category') }}:</strong> {{ $contact->category }}</li>
        <li><strong>{{ __('contacts.name') }}:</strong> {{ $contact->name }}</li>
        <li><strong>{{ __('contacts.email') }}:</strong> {{ $contact->email }}</li>
        <li><strong>{{ __('contacts.message') }}:</strong> {{ $contact->message }}</li>
        <li><strong>{{ __('contacts.status') }}:</strong> {{ $contact->status }}</li>
    </ul>

    {{-- Form untuk balasan admin --}}
    @if($contact->status == 'Dikirim')
        <form action="{{ route('contacts.reply', $contact) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="message">{{ __('contacts.reply_message') }}</label>
                <textarea id="message" name="message" rows="4" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('contacts.reply') }}</button>
        </form>
    @endif

    <a href="{{ route('contacts.index') }}" class="btn btn-secondary">{{ __('contacts.back') }}</a>
</div>
@endsection
