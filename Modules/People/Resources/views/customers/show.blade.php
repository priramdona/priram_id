@extends('layouts.app')

@section('title', __('people.customers') . ' ' . __('people.details'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('people.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">{{ __('people.customers') }}</a></li>
        <li class="breadcrumb-item active">{{ __('people.details') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>{{ __('people.customer_name') }}</th>
                                    <td>{{ $customer->customer_name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('people.email') }}</th>
                                    <td>{{ $customer->customer_email }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('people.phone') }}</th>
                                    <td>{{ $customer->customer_phone }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('people.city') }}</th>
                                    <td>{{ $customer->city }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('people.country') }}</th>
                                    <td>{{ $customer->country }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('people.address') }}</th>
                                    <td>{{ $customer->address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

