@extends('layouts.app')

@section('title', __('privacy.title'))
@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('privacy.breadcrumb_home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('privacy.breadcrumb_privacy') }}</li>
    </ol>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="text-center mb-4">{{ __('privacy.heading') }}</h1>
                        <div class="section">
                            <h2>{{ __('privacy.content.1.title') }}</h2>

                            <p>{{ __('privacy.content.1.p1') }}</p>
                            <p class="lead">{{ __('privacy.content.1.p2') }}</p>
                            <p>{{ __('privacy.content.1.p3') }}</p>

                            <h2>{{ __('privacy.content.2.title') }}</h2>
                            <p>{{ __('privacy.content.2.p1.title') }}</p>
                            <ul>
                                <li ><p><strong>{{ __('privacy.content.2.p1.ul.li1.title') }}</strong>{{ __('privacy.content.2.p1.ul.li1.content') }}</p></li>
                                <li ><p><strong>{{ __('privacy.content.2.p1.ul.li2.title') }}</strong>{{ __('privacy.content.2.p1.ul.li2.content') }}</p></li>
                                <li ><p><strong>{{ __('privacy.content.2.p1.ul.li3.title') }}</strong>{{ __('privacy.content.2.p1.ul.li3.content') }}</p></li>
                                <li ><p><strong>{{ __('privacy.content.2.p1.ul.li4.title') }}</strong>{{ __('privacy.content.2.p1.ul.li4.content') }}</p></li>
                                <li ><p><strong>{{ __('privacy.content.2.p1.ul.li5.title') }}</strong>{{ __('privacy.content.2.p1.ul.li5.content') }}</p></li>
                            </ul>

                            <h2>{{ __('privacy.content.3.title') }}</h2>
                            <p>{{ __('privacy.content.3.p1.title') }}</p>
                            <ul>
                                <li ><p><strong>{{ __('privacy.content.3.p1.ul.li1.title') }}</strong>{{ __('privacy.content.3.p1.ul.li1.content') }}</p></li>
                                <li ><p><strong>{{ __('privacy.content.3.p1.ul.li2.title') }}</strong>{{ __('privacy.content.3.p1.ul.li2.content') }}</p></li>
                                <li ><p><strong>{{ __('privacy.content.3.p1.ul.li3.title') }}</strong>{{ __('privacy.content.3.p1.ul.li3.content') }}</p></li>
                                <li ><p><strong>{{ __('privacy.content.3.p1.ul.li4.title') }}</strong>{{ __('privacy.content.3.p1.ul.li4.content') }}</p></li>
                                <li ><p><strong>{{ __('privacy.content.3.p1.ul.li5.title') }}</strong>{{ __('privacy.content.3.p1.ul.li5.content') }}</p></li>
                                <li ><p><strong>{{ __('privacy.content.3.p1.ul.li6.title') }}</strong>{{ __('privacy.content.3.p1.ul.li6.content') }}</p></li>
                            </ul>

                            <h2>{{ __('privacy.content.4.title') }}</h2>
                            <p>{{ __('privacy.content.4.p1.title') }}</p>
                            <ul>
                                <li ><p><strong>{{ __('privacy.content.4.p1.ul.li1.title') }}</strong>{{ __('privacy.content.4.p1.ul.li1.content') }}</p></li>
                                <li ><p><strong>{{ __('privacy.content.4.p1.ul.li2.title') }}</strong>{{ __('privacy.content.4.p1.ul.li2.content') }}</p></li>
                            </ul>

                            <h2>{{ __('privacy.content.5.title') }}</h2>

                            <p>{{ __('privacy.content.5.p1.title') }}</p>

                            <h2>{{ __('privacy.content.6.title') }}</h2>

                            <p>{{ __('privacy.content.6.p1.title') }}</p>

                            <h2>{{ __('privacy.content.7.title') }}</h2>

                            <p>{{ __('privacy.content.7.p1.title') }}</p>
                            <h2>{{ __('privacy.content.8.title') }}</h2>

                            <p>{{ __('privacy.content.8.p1.title') }}</p>
                            <h2>{{ __('privacy.content.9.title') }}</h2>

                            <p>{{ __('privacy.content.9.p1.title') }}</p>

                            <h2>{{ __('privacy.content.10.title') }}</h2>

                            <p>{{ __('privacy.content.10.p1.title') }}</p>
                            <h2>{{ __('privacy.content.11.title') }}</h2>

                            <p>{{ __('privacy.content.11.p1.title') }}</p>
                            <ul>
                                <li ><p><strong>{{ __('privacy.content.11.p1.ul.li1.title') }}</strong>{{ __('privacy.content.11.p1.ul.li1.content') }}</p></li>
                                <li ><p><strong>{{ __('privacy.content.11.p1.ul.li2.title') }}</strong>{{ __('privacy.content.11.p1.ul.li2.content') }}</p></li>
                                <li ><p><strong>{{ __('privacy.content.11.p1.ul.li3.title') }}</strong>{{ __('privacy.content.11.p1.ul.li3.content') }}</p></li>

                            </ul>
                            <h2>{{ __('privacy.content.12.title') }}</h2>

                            <p>{{ __('privacy.content.12.p1.title') }}</p>
                            <div class="col-lg-6 text-center text-lg-left">
                                <p class="mb-2"><span class="ti-location-pin mr-2">{{ __('privacy.content.12.p1.contact.place') }}</span></p>
                                <div class=" d-block d-sm-inline-block">
                                    <p class="mb-2">
                                        <span class="ti-email mr-2"></span><a class="mr-4" href="mailto:support@priram.com">{{ __('privacy.content.12.p1.contact.email') }}</a>
                                    </p>
                                </div>
                                <div class="d-block d-sm-inline-block">
                                    <p class="mb-0">
                                        <span class="ti-mobile-alt mr-2"></span><a href="tel:+6281314569045">{{ __('privacy.content.12.p1.contact.phone') }}</a>
                                    </p>
                                </div>

                            </div>
                        </div>

                <!-- Tombol Cetak -->
                {{-- <div class="text-center print-btn">
                    <button class="btn btn-primary" onclick="window.print()">Cetak Halaman</button>
                </div> --}}
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
