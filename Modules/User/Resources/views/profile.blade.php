@extends('layouts.app')

@section('title', __('user.profile'))

@section('third_party_stylesheets')
    @include('includes.filepond-css')
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('user.home') }}</a></li>
        <li class="breadcrumb-item active">{{  __('user.profile') }}</li>
    </ol>
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @include('utils.alerts')
                <h3>{{  __('user.profiles.hello') }} <span class="text-primary">{{ auth()->user()->name }}</span>.</h3>
                <p class="font-italic">{{  __('user.profiles.info') }}</p>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            {{-- <div class="form-group">
                                <label for="image">{{ __('user.profiles.profile_image') }} <span class="text-danger">*</span></label>
                                <img style="width: 100px; height: 100px;" class="d-block mx-auto img-thumbnail img-fluid rounded-circle mb-2"
                                     src="{{ auth()->user()->getFirstMediaUrl('avatars', 'thumb') }}" alt="Profile Image">
                                <input id="image" type="file" name="image" accept="image/*">
                            </div> --}}
                            <div class="form-group">
                                <label for="image">{{ __('user.profiles.profile_image') }}</label>
                                <input type="file"
                                        class="form-control-file"
                                        id="imageInput"
                                        name="image"
                                        accept="image/*"
                                        onchange="previewImage(event)">
                                <div class="mt-3">
                                    <!-- Preview Image -->
                                    <img id="imagePreview" src="{{ auth()->user()->image_url ?? asset('images/fallback_profile_image.png') }}" alt="{{ __('products.no_file_selected') }}" style="max-width: 200px;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">{{  __('user.profiles.name') }} <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" required value="{{ auth()->user()->name }}">
                                @error('name')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="phone_number">{{  __('user.profiles.phone') }} <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="phone_number" required value="{{ auth()->user()->phone_number }}">
                                @error('phone_number')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">{{  __('user.profiles.email') }} <span class="text-danger">*</span></label>
                                <input class="form-control" type="email" name="email" required value="{{ auth()->user()->email }}">
                                @error('email')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">{{  __('user.profiles.update_profile') }} <i class="bi bi-check"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('profile.update.password') }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="form-group">
                                <label for="current_password">{{  __('user.profiles.current_password') }} <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="current_password" required>
                                @error('current_password')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">{{  __('user.profiles.current_password') }} <span class="text-danger">*</span></label>
                                <input class="form-control" type="password" name="password" required>
                                @error('password')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">{{  __('user.profiles.current_password') }} <span class="text-danger">*</span></label>
                                <input class="form-control" type="password" name="password_confirmation" required>
                                @error('password_confirmation')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">{{  __('user.profiles.update_password') }} <i class="bi bi-check"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                     <div class="card">
                    <div class="card-body">
                        {{-- <form id="delete_account" name="delete_account" action="{{ route('profile.delete') }}" method="POST">
                            @csrf --}}
                            <p><span class="text-danger">{{  __('user.profiles.delete_info') }}</span> {{  __('user.profiles.delete_description') }}</p>

                            <div class="form-group">
                                <button type="submit" name="submitDelete" id="submitDelete" class="btn btn-danger">{{  __('user.profiles.delete_button') }} <i class="bi bi-trash"></i></button>
                            </div>
                        {{-- </form> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="actionModal" tabindex="-1" role="dialog" aria-labelledby="lbl_payment_action" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="lbl_payment_action">{{  __('user.profiles.delete_confirmation') }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="password_delete">{{  __('user.profiles.current_password') }} <span class="text-danger">*</span></label>
                            <input class="form-control" type="password" name="password_delete"  id="password_delete" required>
                            @error('password_delete')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" name="deleteConfirmation" id="deleteConfirmation">{{  __('user.profiles.button_confirmation') }}</button>
                        <button type="button" class="btn btn-secondary" name="cancelConfirmation" id="cancelConfirmation">{{  __('user.profiles.button_cancel_delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
@endsection

@push('page_scripts')
    @include('includes.filepond-js')
    <script>
        function previewImage(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function() {
            const preview = document.getElementById('imagePreview');
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file); // Membaca file sebagai data URL untuk preview
        }
    }

    </script>
<script>

    const messages = @json(__('user.profiles'));

    document.getElementById('submitDelete').addEventListener('click', function(event) {
        event.preventDefault(); // Mencegah pengiriman form langsung
        Swal.fire({
        title: messages.delete_title,
        text: messages.delete_text,
        icon: 'question',  // Tipe ikon question
        showCancelButton: true,  // Menampilkan tombol cancel
        confirmButtonColor: '#d33',  // Warna tombol confirm
        cancelButtonColor: '#3085d6',  // Warna tombol cancel
        confirmButtonText: messages.button_confirm_delete,
        cancelButtonText: messages.button_cancel_delete,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {$('.swal2-container, .swal2-popup').css('pointer-events', 'auto');},
        }).then((result) => {
            if (result.isConfirmed) {
                $('#actionModal').modal('show');
            }
            else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: messages.cancel_title,
                    text: messages.cancel_text,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    icon: 'error',
                    didOpen: () => {$('.swal2-container, .swal2-popup').css('pointer-events', 'auto');},
                });
                return;
            }
        });
    });
    $(document).on('click', '#deleteConfirmation', function()
    {

        var password_delete = document.getElementById('password_delete').value;
        $.ajax({
                url: "{{ url('/user/delete') }}/",
                method: "GET",
                data: {
                    'password_delete': password_delete,
                },

                dataType: 'json',
                success: function(data) {
                    if (data.status == 'password_invalid'){
                        Swal.fire({
                            title: messages.error_pass_title,
                            text: messages.error_pass_text,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            icon: 'error',
                            didOpen: () => {$('.swal2-container, .swal2-popup').css('pointer-events', 'auto');},
                        });
                        $('#actionModal').modal('hide');
                        return;
                    }else if(data.status == 'error'){
                        Swal.fire({
                            title: messages.error_title,
                            text: messages.error_text,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            icon: 'error',
                            didOpen: () => {$('.swal2-container, .swal2-popup').css('pointer-events', 'auto');},
                        });
                        $('#actionModal').modal('hide');
                        return;
                    }else{
                        Swal.fire({
                            title: messages.deleted_title,
                            text: messages.deleted_text,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            icon: 'error',
                            didOpen: () => {$('.swal2-container, .swal2-popup').css('pointer-events', 'auto');},
                        });
                        document.getElementById('logout-form').submit();
                    }
                }
            });
    });
</script>
@endpush


