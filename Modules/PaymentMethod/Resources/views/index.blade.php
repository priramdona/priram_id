@extends('paymentmethod::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('paymentmethod.name') !!}</p>
@endsection
