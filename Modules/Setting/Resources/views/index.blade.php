@extends('layouts.app')

@section('title', __('general_settings.edit_settings'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('general_settings.home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('general_settings.settings') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                @include('utils.alerts')
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ __('general_settings.general_settings') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="company_name">{{ __('general_settings.company_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="company_name" value="{{ $settings->company_name }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="company_email">{{ __('general_settings.company_email') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="company_email" value="{{ $settings->company_email }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="company_phone">{{ __('general_settings.company_phone') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="company_phone" value="{{ $settings->company_phone }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-4" hidden>
                                    <div class="form-group">
                                        <label for="default_currency_id">{{ __('general_settings.default_currency') }} <span class="text-danger">*</span></label>
                                        <select name="default_currency_id" id="default_currency_id" class="form-control" required>
                                            @foreach(\Modules\Currency\Entities\Currency::where('business_id',Auth::user()->business_id)->get() as $currency)
                                                <option {{ $settings->default_currency_id == $currency->id ? 'selected' : '' }} value="{{ $currency->id }}">{{ $currency->currency_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4" hidden>
                                    <div class="form-group">
                                        <label for="default_currency_position">{{ __('general_settings.default_currency_position') }} <span class="text-danger">*</span></label>
                                        <select name="default_currency_position" id="default_currency_position" class="form-control" required>
                                            <option {{ $settings->default_currency_position == 'prefix' ? 'selected' : '' }} value="prefix">{{ __('general_settings.prefix') }}</option>
                                            <option {{ $settings->default_currency_position == 'suffix' ? 'selected' : '' }} value="suffix">{{ __('general_settings.suffix') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="default_language">{{ __('general_settings.language') }} <span class="text-danger">*</span></label>
                                        <select name="default_language" id="default_language" class="form-control" required>
                                            <option {{ $settings->locale == 'id' ? 'selected' : '' }} value="id">{{ __('general_settings.indonesia') }}</option>
                                            <option {{ $settings->locale == 'en' ? 'selected' : '' }} value="en">{{ __('general_settings.english') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4" hidden>
                                    <div class="form-group">
                                        <label for="notification_email">{{ __('general_settings.notification_email') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="notification_email" value="{{ $settings->notification_email }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="company_address">{{ __('general_settings.company_address') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="company_address" value="{{ $settings->company_address }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> {{ __('general_settings.save_changes') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- <div class="col-lg-12">
                @if (session()->has('settings_smtp_message'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <div class="alert-body">
                            <span>{{ session('settings_smtp_message') }}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ __('general_settings.mail_settings') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.smtp.update') }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="mail_mailer">{{ __('general_settings.mail_mailer') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="mail_mailer" value="{{ env('MAIL_MAILER') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="mail_host">{{ __('general_settings.mail_host') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="mail_host" value="{{ env('MAIL_HOST') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="mail_port">{{ __('general_settings.mail_port') }} <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="mail_port" value="{{ env('MAIL_PORT') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="mail_username">{{ __('general_settings.mail_username') }}</label>
                                        <input type="text" class="form-control" name="mail_username" value="{{ env('MAIL_USERNAME') }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="mail_password">{{ __('general_settings.mail_password') }}</label>
                                        <input type="password" class="form-control" name="mail_password" value="{{ env('MAIL_PASSWORD') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="mail_encryption">{{ __('general_settings.mail_encryption') }}</label>
                                        <input type="text" class="form-control" name="mail_encryption" value="{{ env('MAIL_ENCRYPTION') }}">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label for="mail_from_address">{{ __('general_settings.mail_from_address') }}</label>
                                        <input type="email" class="form-control" name="mail_from_address" value="{{ env('MAIL_FROM_ADDRESS') }}">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label for="mail_from_name">{{ __('general_settings.mail_from_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="mail_from_name" value="{{ env('MAIL_FROM_NAME') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> {{ __('general_settings.save_changes') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@endsection
