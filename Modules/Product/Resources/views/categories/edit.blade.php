@extends('layouts.app')

@section('title', __('products.edit_category'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('products.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('products.products') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('product-categories.index') }}">{{ __('products.categories') }}</a></li>
        <li class="breadcrumb-item active">{{ __('products.edit_category') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-7">
                @include('utils.alerts')
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('product-categories.update', $category->id) }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="form-group">
                                <label class="font-weight-bold" for="category_code">{{ __('products.category_code') }} <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="category_code" readonly value="{{ $category->category_code }}">
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold" for="category_name">{{ __('products.category_name') }} <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="category_name" required value="{{ $category->category_name }}">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">{{ __('products.edit_category') }} <i class="bi bi-check"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

