@extends('layouts.app')

@section('title', __('user.add_user'))

@section('third_party_stylesheets')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet"/>
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
          rel="stylesheet">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('user.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('user.users') }}</a></li>
        <li class="breadcrumb-item active">{{ __('user.add_user') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                    <div class="form-group">
                        <button class="btn btn-primary">{{ __('user.add_user') }} <i class="bi bi-check"></i></button>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">{{ __('user.name') }} <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="name" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="email">{{ __('user.email') }} <span class="text-danger">*</span></label>
                                        <input class="form-control" type="email" name="email" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="phone_number">{{ __('user.phone_number') }} <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="phone_number" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="password">{{ __('user.password') }} <span class="text-danger">*</span></label>
                                        <input class="form-control" type="password" name="password" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">{{ __('user.confirm_password') }} <span class="text-danger">*</span></label>
                                        <input class="form-control" type="password" name="password_confirmation" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="role">{{ __('user.role') }} <span class="text-danger">*</span></label>
                                <select class="form-control" name="role" id="role" required>
                                    <option value="" selected disabled>{{ __('user.select_role') }}</option>
                                    @foreach(\Spatie\Permission\Models\Role::where('name', '!=', 'Super Admin')->get()->where('business_id',Auth::user()->business_id) as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="is_active">{{ __('user.status') }} <span class="text-danger">*</span></label>
                                <select class="form-control" name="is_active" id="is_active" required>
                                    <option value="" selected disabled>{{ __('user.select_status') }}</option>
                                    <option value="1">{{ __('user.active') }}</option>
                                    <option value="2">{{ __('user.deactive') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="image">{{ __('user.profile_image') }}</label>
                                <input type="file"
                                        class="form-control-file"
                                        id="imageInput"
                                        name="image"
                                        accept="image/*"
                                        onchange="previewImage(event)">
                                <div class="mt-3">
                                    <!-- Preview Image -->
                                    <img id="imagePreview" src="{{ $user->image_url ?? asset('images/fallback_profile_image.png') }}" alt="{{ __('products.no_file_selected') }}" style="max-width: 200px;">
                                </div>
                                {{-- <label for="image">{{ __('user.profile_image') }} <span class="text-danger">*</span></label>
                                <input id="image" type="file" name="image" data-max-file-size="500KB"> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('third_party_scripts')
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
@endsection

@push('page_scripts')

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
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType
        );
        const fileElement = document.querySelector('input[id="image"]');
        const pond = FilePond.create(fileElement, {
            acceptedFileTypes: ['image/png', 'image/jpg', 'image/jpeg'],
        });
        FilePond.setOptions({
            server: {
                url: "{{ route('filepond.upload') }}",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            }
        });
    </script>
@endpush


