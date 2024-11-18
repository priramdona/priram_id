@extends('layouts.app')

@section('title', __('expense.add_expense'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('expense.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">{{ __('expense.expenses') }}</a></li>
        <li class="breadcrumb-item active">{{ __('expense.add_expense') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <form id="expense-form" action="{{ route('expenses.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                    <div class="form-group">
                        <button class="btn btn-primary">{{ __('expense.add_expense') }} <i class="bi bi-check"></i></button>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="reference">{{ __('expense.reference') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="reference" required readonly value="EXP">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="date">{{ __('expense.date') }} <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="date" required value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="category_id">{{ __('expense.category') }} <span class="text-danger">*</span></label>
                                        <select name="category_id" id="category_id" class="form-control" required>
                                            <option value="" selected>{{ __('expense.select_category') }}</option>
                                            @foreach(\Modules\Expense\Entities\ExpenseCategory::where('business_id',Auth::user()->business_id)->get() as $category)
                                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="amount">{{ __('expense.amount') }} <span class="text-danger">*</span></label>
                                        <input id="amount" type="text" class="form-control" name="amount" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="details">{{ __('expense.details') }}</label>
                                <textarea class="form-control" rows="6" name="details"></textarea>
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

            $('#expense-form').submit(function () {
                var amount = $('#amount').maskMoney('unmasked')[0];
                $('#amount').val(amount);
            });
        });
    </script>
@endpush

