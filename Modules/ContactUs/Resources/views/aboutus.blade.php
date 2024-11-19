@extends('layouts.app')

@section('content')

    <div class="container mt-5">

        <div class="card mx-auto mt-4 p-4">
            <h2 class="text-center mb-3">{{ __('contacts.title') }}</h2>
            <p>{{ __('contacts.description') }}</p>

            <h4 class="mt-4">{{ __('contacts.features_title') }}</h4>
            <ul class="feature-list">
                <li>{{ __('contacts.features.stock_management') }}</li>
                <li>{{ __('contacts.features.payment_processing') }}</li>
                <li>{{ __('contacts.features.selforder_integration') }}</li>
                <li>{{ __('contacts.features.delivery_integration') }}</li>
                <li>{{ __('contacts.features.stay_integration') }}</li>
                <li>{{ __('contacts.features.quotation_feature') }}</li>
                <li>{{ __('contacts.features.income_feature') }}</li>
                <li>{{ __('contacts.features.payment_processing') }}</li>
                <li>{{ __('contacts.features.barcode_scanning') }}</li>
                <li>{{ __('contacts.features.reporting') }}</li>
                <li>{{ __('contacts.features.access_role') }}</li>
                <li>{{ __('contacts.features.multi_language_support') }}</li>
                <li>{{ __('contacts.features.data_security') }}</li>
            </ul>

            <hr>
            <h4 class="mt-4">{{ __('contacts.contact') }}</h4>
            <p>Email : <a href="mailto:{{ __('contacts.email_internal') }}" class="text-primary">{{ __('contacts.email_internal') }}</a></p>
        </div>
    </div>

@endsection
