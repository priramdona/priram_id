@extends('layouts.app')

@section('title', __('people.add_customer'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('people.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">{{ __('people.customers') }}</a></li>
        <li class="breadcrumb-item active">{{ __('people.add_customer') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                    <div class="form-group">
                        <button class="btn btn-primary">{{ __('people.add_customer') }} <i class="bi bi-check"></i></button>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="first_name">{{ __('people.first_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_first_name" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="last_name">{{ __('people.last_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_last_name" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="gender">{{ __('people.gender') }} <span class="text-danger">*</span></label>
                                        <select name="gender" id="gender" class="form-control" required>
                                            <option value="" selected>{{ __('people.select') }}</option>
                                            <option value="MALE">{{ __('people.male') }}</option>
                                            <option value="FEMALE">{{ __('people.female') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="dob">{{ __('people.date_of_birth') }} <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="dob" required value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label for="customer_email">{{ __('people.email') }} <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="customer_email" required>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="customer_phone">{{ __('people.phone') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_phone" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <label for="address">{{ __('people.address') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="address" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="city">{{ __('people.city') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="city" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="province">{{ __('people.province') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="province" required>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="postalCode">{{ __('people.postal_code') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="postalCode" required>
                                    </div>
                                </div>
                                <div class="form-group" hidden>
                                    <label for="country">{{ __('people.country') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="country" value="ID" required>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

