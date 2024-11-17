@extends('layouts.app')

@section('title', 'Product Details')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{  __('selforder.home') }}</a></li>
        {{-- <li class="breadcrumb-item"><a href="">{{  __('selforder.home') }}</a></li> --}}
        <li class="breadcrumb-item active">{{  __('selforder.selforder') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">

        <div class="row">
            <div class="col-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        <figure class="text-center">
                            <img src="{{ asset('images/mobileorder/mobileorder1.jpg') }}" alt="Product Image" class="img-fluid img-thumbnail mb-2">
                            <figcaption class="figure-caption text-muted mt-2">{{ __('selforder.step.mobile.1') }}</figcaption>
                        </figure>
                        <figure class="text-center">
                            <img src="{{ asset('images/mobileorder/mobileorder2.jpg') }}" alt="Product Image" class="img-fluid img-thumbnail mb-2">
                            <figcaption class="figure-caption text-muted mt-2">{{ __('selforder.step.mobile.2') }}</figcaption>
                        </figure>
                        <figure class="text-center">
                            <img src="{{ asset('images/mobileorder/mobileorder3.jpg') }}" alt="Product Image" class="img-fluid img-thumbnail mb-2">
                            <figcaption class="figure-caption text-muted mt-2">{{ __('selforder.step.mobile.3') }}</figcaption>
                        </figure>
                        <figure class="text-center">
                            <img src="{{ asset('images/mobileorder/mobileorder4.jpg') }}" alt="Product Image" class="img-fluid img-thumbnail mb-2">
                            <figcaption class="figure-caption text-muted mt-2">{{ __('selforder.step.mobile.4') }}</figcaption>
                        </figure>
                        <figure class="text-center">
                            <img src="{{ asset('images/mobileorder/mobileorder5.jpg') }}" alt="Product Image" class="img-fluid img-thumbnail mb-2">
                            <figcaption class="figure-caption text-muted mt-2">{{ __('selforder.step.mobile.5') }}</figcaption>
                        </figure>
                        <figure class="text-center">
                            <img src="{{ asset('images/mobileorder/mobileorder6.jpg') }}" alt="Product Image" class="img-fluid img-thumbnail mb-2">
                            <figcaption class="figure-caption text-muted mt-2">{{ __('selforder.step.mobile.6') }}</figcaption>
                        </figure>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="card h-100">
                    <div class="card-body">
                        <form id="product-form" action="{{ route('selforder.business.update', $business->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <div class="product-details">
                                <div class="detail-item">
                                    <span class="detail-label">{{ __('selforder.type.name') }}</span>
                                    <span class="detail-value">{{ $selforderType->name }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">{{ __('selforder.type.descriptions') }}</span>
                                    <span class="detail-value">{{ $selforderType->description }}</span>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover">

                                        <tr>
                                            <th>{{ __('selforder.business.subject') }}</th>
                                            <td>
                                                <input type="text" id="subject" name="subject" class="form-control" value="{{ $selforderBusiness->subject ?? '' }}"/>

                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('selforder.business.information') }}</th>
                                            <td> <input type="text" id="captions" name="captions"  rows="4 " class="form-control" value="{{ $selforderBusiness->captions ?? '' }}"/></td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('selforder.business.status') }}</th>
                                            <td>
                                                <select class="form-control" name="status" id="status" required>
                                                    <option {{ $selforderBusiness->status ?? '' == '' ? 'selected' : '' }} value="" disabled>{{ __('selforder.business.status_select') }}</option>
                                                    <option {{ $selforderBusiness->status ?? '' == '0' ? 'selected' : '' }} value="0">{{ __('selforder.business.status_notactive') }}</option>
                                                    <option {{ $selforderBusiness->status  ?? '' == '1' ? 'selected' : '' }} value="1">{{ __('selforder.business.status_active') }}</option>

                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                    @include('utils.alerts')
                                    <div class="form-group">
                                        <button class="btn btn-primary">{{ __('selforder.business.button_update') }}<i class="bi bi-check"></i></button>
                                    </div>
                                </div>

                                <div class="container">
                                    <p><span style="font-size: 12px; font-weight: bold; color: red;">{{ __('selforder.info_attention') }}</span></p>
                                    <p><span style="font-size: 12px;  font-weight: bold;">{{ __('selforder.info_qrcode_mobile') }}</span></p>
                                    <p class="text-muted">{{ $link }}</p>
                                    <figure class="text-center">
                                        {{-- <img id="qrCodeImage" src="data:image/png;base64, {{ $qrCode }}" alt="QR Code" class="img-fluid img-thumbnail"> --}}
                                        <img id="qrCodeImage" src="data:image/png;base64, {{ $qrCode }}" alt="QR Code" class="img-fluid img-thumbnail" style="width: 300px; height: 300px;">

                                        <figcaption class="figure-caption text-muted mt-2">
                                            {{ __('selforder.info_qrcode_show_mobile') }}
                                        </figcaption>
                                        <!-- Tombol download QR code -->
                                        <div>
                                            <span style="font-size: 12px; font-weight: bold;">{{ __('selforder.info_qrcode_action_mobile') }}</span>
                                        </div>
                                        <button type="button" id="downloadQrButton" class="btn btn-primary mt-3">{{ __('selforder.button_download') }}</button>
                                        <button type="button" id="copyUrlButton"  onclick="copyToClipboard()"class="btn btn-primary mt-3">{{ __('selforder.button_copy') }}</button>

                                    </figure>
                                </div>

                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('page_css')
<style>
    figure {
        display: inline-block;
        text-align: center;
    }

    figure img {
        border-radius: 10px;
        transition: transform 0.3s ease;
    }

    figure img:hover {
        transform: scale(1.05);
    }

    figure .figure-caption {
        font-style: italic;
        color: #666;
        font-size: 0.9em;
    }

    .product-details {
        display: flex;
        flex-wrap: wrap;
    }
    .detail-item {
        width: 100%;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }
    .detail-label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }
    .detail-value {
        display: block;
    }
    @media (min-width: 768px) {
        .detail-item {
            width: 50%;
            padding-right: 15px;
        }
    }
    @media (min-width: 992px) {
        .detail-item {
            width: 33.33%;
        }
    }
</style>
@endpush

@push('page_scripts')

<script>
    const urlToCopy = "{{ $url }}";

    // Fungsi untuk menyalin URL ke clipboard
    function copyToClipboard() {
        navigator.clipboard.writeText(urlToCopy)
            .then(() => {
                Swal.fire({
                    title: 'Copy Success',
                    text: 'You can paste or share it!',
                    icon: 'success',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                            $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                    },
                });
            })
            .catch(err => {
                Swal.fire({
                    title: 'Error Copy',
                    text: "Gagal menyalin URL ke clipboard: ", err,
                    icon: 'error',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                            $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                    },
                });
            });
    }

    document.getElementById('downloadQrButton').addEventListener('click', function () {
        // Mendapatkan data src dari gambar QR code
        const qrImage = document.getElementById('qrCodeImage').src;

        // Mendapatkan tanggal saat ini
        const currentDate = new Date();
        const day = String(currentDate.getDate()).padStart(2, '0');
        const month = String(currentDate.getMonth() + 1).padStart(2, '0'); // Bulan ditambahkan 1 karena dimulai dari 0
        const year = currentDate.getFullYear();

        // Menyusun nama file dengan format DD-MM-YYYY
        const fileName = `QRCode_${day}-${month}-${year}.png`;

        // Membuat elemen anchor secara dinamis
        const link = document.createElement('a');
        link.href = qrImage;
        link.download = fileName; // Nama file dengan tanggal
        link.style.display = 'none';

        // Menambahkan link ke DOM, memicu klik, lalu menghapusnya
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
</script>

@endpush
