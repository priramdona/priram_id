@extends('layouts.app')

@section('title', 'Edit Customer')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <form action="{{ route('customers.update', $customer) }}" method="POST">
            @csrf
            @method('patch')
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                    <div class="form-group">
                        <button class="btn btn-primary">Update Customer <i class="bi bi-check"></i></button>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_first_name" value="{{ $customer->customer_first_name }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_last_name" value="{{ $customer->customer_last_name }}"  required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="first_name">Gender <span class="text-danger">*</span></label>
                                        {{-- <input type="text" class="form-control" name="gender" required> --}}
                                        <select name="gender" id="gender" class="form-control" required>
                                            @if( $customer->gender == '' )
                                                <option value="" selected>Select</option>
                                            @else
                                                <option value="">Select</option>
                                            @endif

                                            @if( $customer->gender == 'MALE')
                                                <option value="MALE" selected>Male</option>
                                            @else
                                                <option value="MALE">Male</option>
                                            @endif

                                            @if( $customer->gender === 'FEMALE')
                                                <option value="FEMALE" selected>Female</option>
                                            @else
                                                <option value="FEMALE">Female</option>
                                            @endif

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="last_name">Date of birth <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="dob" required value="{{ \Carbon\Carbon::parse($customer->dob)->format('Y-m-d') }}">

                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label for="customer_email">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="customer_email"  value="{{ $customer->customer_email }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="customer_phone">Phone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_phone"  value="{{ $customer->customer_phone }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <label for="address">Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="address" value="{{ $customer->address }}" required>
                                    </div>
                                </div>

                            </div>

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="city">City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="city"  value="{{ $customer->city }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="province">Province <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="province" required>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="postalCode">Postal Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="postalCode"  value="{{ $customer->postal_code }}" required>
                                    </div>
                                </div>
                                    <div class="form-group" hidden>
                                        <label for="country">Country <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="country"  value="{{ $customer->country }}" required>
                                    </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

