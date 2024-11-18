@extends('layouts.app')

@section('title', __('income.update_income'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('income.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('incomes.index') }}">{{ __('income.incomes') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('income-categories.index') }}">{{ __('income.income_categories') }}</a></li>
        <li class="breadcrumb-item active">{{ __('income.update_income') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-7">
                @include('utils.alerts')
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('income-categories.update', $incomeCategory) }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="form-group">
                                <label for="category_name">{{ __('income.category_name') }} <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="category_name" required value="{{ $incomeCategory->category_name }}">
                            </div>
                            <div class="form-group">
                                <label for="category_description">{{ __('income.category_description') }}</label>
                                <textarea class="form-control" name="category_description" id="category_description" rows="5">{{ $incomeCategory->category_description }}</textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">{{ __('income.update_income') }} <i class="bi bi-check"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

