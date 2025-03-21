@extends('layouts.app')

@section('title', __('adjustment.add_adjustment'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('adjustment.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('adjustments.index') }}">{{ __('adjustment.adjustments') }}</a></li>
        <li class="breadcrumb-item active">{{ __('adjustment.add_adjustment') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-12">
                <livewire:search-product/>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @include('utils.alerts')
                        <form action="{{ route('adjustments.store') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="reference">{{ __('adjustment.reference') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="reference" required readonly value="ADJ">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="date">{{ __('adjustment.date') }} <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="date" required value="{{ now()->format('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                            <livewire:adjustment.product-table/>
                            <div class="form-group">
                                <label for="note">{{ __('adjustment.note') }} ({{ __('adjustment.if_needed') }})</label>
                                <textarea name="note" id="note" rows="5" class="form-control"></textarea>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('adjustment.add_adjustment') }} <i class="bi bi-check"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
