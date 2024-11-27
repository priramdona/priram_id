@extends('layouts.app')

@section('title', __('report.sales_return_report'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('menu.home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('report.sales_return_report') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <livewire:reports.sales-return-report :customers="\Modules\People\Entities\Customer::where('business_id', Auth::user()->business_id)->get()"/>
    </div>
@endsection
