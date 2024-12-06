@extends('layouts.app')
@section('title', __('terms.title'))
@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('terms.breadcrumb_home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('terms.breadcrumb_terms') }}</li>
    </ol>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="text-center mb-4">{{ __('terms.heading') }}</h1>
                    <div class="section">
                        <h2>{{ __('terms.content.a.title') }}</h2>
                        <p class="lead">{{ __('terms.content.a.p1') }}</p>

                        <p>{{ __('terms.content.a.p2') }}</p>
                        <p>{{ __('terms.content.a.p3') }}</p>

                        <h2>{{ __('terms.content.b.title') }}</h2>

                        <ul>
                            <li ><p>{{ __('terms.content.b.p1') }}</p></li>
                            <li ><p>{{ __('terms.content.b.p2') }}</p></li>
                         </ul>
                         <h2>{{ __('terms.content.c.title') }}</h2>
                         <h2>{{ __('terms.content.c.1.title') }}</h2>

                        <ul>
                            <li ><strong>{{ __('terms.content.c.1.li1.title') }} </strong><p>{{ __('terms.content.c.1.li1.content') }}</p></li>
                            <li ><strong>{{ __('terms.content.c.1.li2.title') }} </strong><p>{{ __('terms.content.c.1.li2.content') }}</p></li>
                            <li ><strong>{{ __('terms.content.c.1.li3.title') }} </strong><p>{{ __('terms.content.c.1.li3.content') }}</p></li>
                            <li ><strong>{{ __('terms.content.c.1.li4.title') }} </strong><p>{{ __('terms.content.c.1.li4.content') }}</p></li>
                        </ul>
                        <h2>{{ __('terms.content.c.2.title') }}</h2>

                        <ul>
                            <li ><p><strong>{{ __('terms.content.c.2.li1.title') }} </strong>{{ __('terms.content.c.2.li1.content') }}</p></li>

                            <li ><p><strong>{{ __('terms.content.c.2.li2.title') }} </strong>{{ __('terms.content.c.2.li2.content') }}</p></li>
                            <li ><p><strong>{{ __('terms.content.c.2.li3.title') }} </strong>{{ __('terms.content.c.2.li3.content') }}</p></li>
                            <li ><p><strong>{{ __('terms.content.c.2.li4.title') }} </strong>{{ __('terms.content.c.2.li4.content') }}</p></li>
                            <li ><p><strong>{{ __('terms.content.c.2.li5.title') }} </strong>{{ __('terms.content.c.2.li5.content') }}</p></li>
                                <ul>
                                    <li><p><strong>{{ __('terms.content.c.2.li5.li1.title') }} </strong>{{ __('terms.content.c.2.li5.li1.content') }}</p></li>
                                    <li><p><strong>{{ __('terms.content.c.2.li5.li2.title') }} </strong>{{ __('terms.content.c.2.li5.li2.content') }}</p></li>
                                    <li><p><strong>{{ __('terms.content.c.2.li5.li3.title') }} </strong>{{ __('terms.content.c.2.li5.li3.content') }}</p></li>
                                </ul>
                            <li ><p><strong>{{ __('terms.content.c.2.li6.title') }} </strong>{{ __('terms.content.c.2.li6.content') }}</p></li>
                            <li ><p><strong>{{ __('terms.content.c.2.li7.title') }} </strong>{{ __('terms.content.c.2.li7.content') }}</p></li>
                            <li ><p><strong>{{ __('terms.content.c.2.li8.title') }} </strong>{{ __('terms.content.c.2.li8.content') }}</p></li>
                            <li ><p><strong>{{ __('terms.content.c.2.li9.title') }} </strong>{{ __('terms.content.c.2.li9.content') }}</p></li>
                            <li ><p><strong>{{ __('terms.content.c.2.li10.title') }} </strong>{{ __('terms.content.c.2.li10.content') }}</p></li>
                            <li ><p><strong>{{ __('terms.content.c.2.li11.title') }} </strong>{{ __('terms.content.c.2.li11.content') }}</p></li>
                            <li ><p><strong>{{ __('terms.content.c.2.li12.title') }} </strong>{{ __('terms.content.c.2.li12.content') }}</p></li>
                            <li ><p><strong>{{ __('terms.content.c.2.li13.title') }} </strong>{{ __('terms.content.c.2.li13.content') }}</p></li>
                            <li ><p><strong>{{ __('terms.content.c.2.li14.title') }} </strong>{{ __('terms.content.c.2.li14.content') }}</p></li>
                            <li ><p><strong>{{ __('terms.content.c.2.li15.title') }} </strong>{{ __('terms.content.c.2.li15.content') }}</p></li>
                            <li ><p><strong>{{ __('terms.content.c.2.li16.title') }} </strong>{{ __('terms.content.c.2.li16.content') }}</p></li>
                            <li ><p><strong>{{ __('terms.content.c.2.li17.title') }} </strong>{{ __('terms.content.c.2.li17.content') }}</p></li>
                            <li ><p><strong>{{ __('terms.content.c.2.li18.title') }} </strong>{{ __('terms.content.c.2.li18.content') }}</p></li>
                        </ul>

                        <h2>{{ __('terms.content.c.3.title') }}</h2>
                        <ul>
                            <li ><p>{{ __('terms.content.c.3.li1') }}</p></li>
                            <li ><p>{{ __('terms.content.c.3.li2') }}</p></li>
                            <li ><p>{{ __('terms.content.c.3.li3') }}</p></li>
                        </ul>

                        <h2>{{ __('terms.content.c.4.title') }}</h2>
                        <ul>
                            <li ><p><strong>{{ __('terms.content.c.4.li1.title') }} </strong>{{ __('terms.content.c.4.li1.content') }}</p></li>

                            <li ><p><strong>{{ __('terms.content.c.4.li2.title') }} </strong>{{ __('terms.content.c.4.li2.content') }}</p></li>
                            <ul>
                                <li ><p>{{ __('terms.content.c.4.li2.li1.content') }}</p></li>
                                <li ><p>{{ __('terms.content.c.4.li2.li2.content') }}</p></li>
                                <li ><p>{{ __('terms.content.c.4.li2.li3.content') }}</p></li>
                                <li ><p>{{ __('terms.content.c.4.li2.li4.content') }}</p></li>
                                <li ><p>{{ __('terms.content.c.4.li2.li5.content') }}</p></li>
                                <li ><p>{{ __('terms.content.c.4.li2.li6.content') }}</p></li>
                            </ul>
                        </ul>

                        <h2>{{ __('terms.content.c.5.title') }}</h2>
                        <ul>
                            <li ><p>{{ __('terms.content.c.5.li1') }}</p></li>
                            <li ><p>{{ __('terms.content.c.5.li2') }}</p></li>
                            <li ><p>{{ __('terms.content.c.5.li3') }}</p></li>
                            <li ><p>{{ __('terms.content.c.5.li4') }}</p></li>
                            <li ><p>{{ __('terms.content.c.5.li5') }}</p></li>
                        </ul>

                        <h2>{{ __('terms.content.c.6.title') }}</h2>
                            <p>{{ __('terms.content.c.6.p') }}</p>
                        <h2>{{ __('terms.content.c.7.title') }}</h2>
                        <ul>
                            <li ><p><strong>{{ __('terms.content.c.7.li1.title') }} </strong></p><p>{{ __('terms.content.c.7.li1.content') }}</p></li>
                            <li ><p><strong>{{ __('terms.content.c.7.li2.title') }} </strong></p><p>{{ __('terms.content.c.7.li2.content') }}</p></li>
                            <li ><p><strong>{{ __('terms.content.c.7.li3.title') }} </strong></p><p>{{ __('terms.content.c.7.li3.content') }}</p></li>
                        </ul>
                        <h2>{{ __('terms.content.c.8.title') }}</h2>
                        <ul>
                            <li ><p><strong>{{ __('terms.content.c.8.li1.title') }} </strong></p><p>{{ __('terms.content.c.8.li1.content') }}</p></li>
                            <li><p><strong>{{ __('terms.content.c.8.li2.title') }} </strong></p>
                                <p>{{ __('terms.content.c.8.li2.content_1') }}</p>
                                <p>{{ __('terms.content.c.8.li2.content_2') }}</p>
                            </li>
                        </ul>

                        <h2>{{ __('terms.content.c.9.title') }}</h2>

                        <p>{{ __('terms.content.c.9.content') }}</p>

                        <ul>
                            <li ><p>{{ __('terms.content.c.9.li1') }}</p></li>
                            <li ><p>{{ __('terms.content.c.9.li2') }}</p></li>
                            <li ><p>{{ __('terms.content.c.9.li3') }}</p></li>
                            <li ><p>{{ __('terms.content.c.9.li4') }}</p></li>
                            <li ><p>{{ __('terms.content.c.9.li5') }}</p></li>
                            <li ><p>{{ __('terms.content.c.9.li6') }}</p></li>
                        </ul>

                        <h2>{{ __('terms.content.c.10.title') }}</h2>

                        <p>{{ __('terms.content.c.10.content') }}</p>

                        <ul>
                            <li ><p>{{ __('terms.content.c.10.li1') }}</p></li>
                            <li ><p>{{ __('terms.content.c.10.li2') }}</p></li>
                            <li ><p>{{ __('terms.content.c.10.li3') }}</p></li>

                        </ul>
                        <h2>{{ __('terms.content.c.11.title') }}</h2>

                        <p>{{ __('terms.content.c.11.content') }}</p>
                        <h2>{{ __('terms.content.c.12.title') }}</h2>

                        <p>{{ __('terms.content.c.12.content_1') }}</p>
                        <p>{{ __('terms.content.c.12.content_2.p1') }} <strong>{{ __('terms.content.c.12.content_2.p2') }}</strong>{{ __('terms.content.c.12.content_2.p3') }}</p>
                        <p>{{ __('terms.content.c.12.content_3.p1') }}  <strong>{{ __('terms.content.c.12.content_3.p2') }}</strong> {{ __('terms.content.c.12.content_3.p3') }}</p>
                        <p>{{ __('terms.content.c.12.content_4') }}</p>
                        <h2>{{ __('terms.content.c.13.title') }}</h2>

                        <p>{{ __('terms.content.c.13.content') }}</p>
                        <h2>{{ __('terms.content.c.14.title') }}</h2>
                        <ul>
                            <li ><p>{{ __('terms.content.c.14.li1') }}</p></li>
                            <li ><p>{{ __('terms.content.c.14.li2') }}</p></li>
                            <li ><p>{{ __('terms.content.c.14.li3') }}</p></li>
                        </ul>

                    </div>

                    <!-- Tombol Cetak -->
                    {{-- <div class="text-center print-btn">
                        <button class="btn btn-primary" onclick="window.print()">Cetak Halaman</button>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


@endsection
@push('page_css')
<style>
    body { font-family: Arial, sans-serif; }
    h1, h2 { color: #0056b3; }
    .section { margin-bottom: 1.5rem; }
    .container { max-width: 800px; }
    .print-btn { margin-top: 20px; }
</style>
@endpush
