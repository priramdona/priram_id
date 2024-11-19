@extends('layouts.app')

@section('content')
<div class="container mt-5">

    <div class="card mx-auto mt-4 p-4">
    <h1>{{ __('contacts.contact_detail') }}</h1>
    <ul>
        <li><strong>{{ __('contacts.category') }}:</strong> {{ $contact->category }}</li>
        <li><strong>{{ __('contacts.name') }}:</strong> {{ $contact->name }}</li>
        <li><strong>{{ __('contacts.email') }}:</strong> {{ $contact->email }}</li>
        <li><strong>{{ __('contacts.message') }}:</strong> {{ $contact->message }}</li>
        <li><strong>{{ __('contacts.status') }}:</strong> {{ $contact->status }}</li>
    </ul>
    <a href="{{ route('contacts.index') }}" class="btn btn-secondary">{{ __('contacts.back') }}</a>
</div>
</div>
@endsection
