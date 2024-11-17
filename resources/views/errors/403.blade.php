@extends('errors.illustrated-layout')

@section('code', '403 🤐')

@section('title', __('Unauthorized'))

@section('image')
    <div style="background-image: url(https://picsum.photos/seed/picsum/1920/1080);" class="absolute pin bg-no-repeat md:bg-left lg:bg-center bg-cover"></div>
@endsection

@section('message', __('Maaf anda tidak punya Izin untuk halaman ini..'))
