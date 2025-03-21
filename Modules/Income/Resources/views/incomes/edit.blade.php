@extends('layouts.app')

@section('title', __('income.create_income'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('income.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('incomes.index') }}">{{ __('income.incomes') }}</a></li>
        <li class="breadcrumb-item active">{{ __('income.edit') }}</li>
    </ol>
@endsection
@section('content')
    <div class="container-fluid">
        <form id="income-form" action="{{ route('incomes.update', $income) }}" method="POST">
            @csrf
            @method('patch')
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                    <div class="form-group">
                        <button class="btn btn-primary">{{ __('income.update_income') }} <i class="bi bi-check"></i></button>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="reference">{{ __('income.reference') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="reference" required value="{{ $income->reference }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="date">{{ __('income.date') }} <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="date" required value="{{ $income->getAttributes()['date'] }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="category_id">{{ __('income.category') }} <span class="text-danger">*</span></label>
                                        <select name="category_id" id="category_id" class="form-control" required>
                                            @foreach(\Modules\Income\Entities\IncomeCategory::where('business_id',auth::user()->business_id)->get() as $category)
                                                <option {{ $category->id == $income->category_id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="amount">{{ __('income.amount') }} <span class="text-danger">*</span></label>
                                        <input id="amount" type="text" class="form-control" name="amount" required value="{{ $income->amount }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="details">{{ __('income.details') }}</label>
                                <textarea class="form-control" rows="6" name="details">{{ $income->details }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('page_scripts')
    <script src="{{ asset('js/jquery-mask-money.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#amount').maskMoney({
                prefix:'{{ settings()->currency->symbol }}',
                thousands:'{{ settings()->currency->thousand_separator }}',
                decimal:'{{ settings()->currency->decimal_separator }}',
            });

            $('#amount').maskMoney('mask');

            $('#income-form').submit(function () {
                var amount = $('#amount').maskMoney('unmasked')[0];
                $('#amount').val(amount);
            });
        });
    </script>
@endpush
