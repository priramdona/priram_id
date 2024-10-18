@extends('layouts.app')

@section('title', 'Product Details')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
        <li class="breadcrumb-item active">Details</li>
    </ol>
@endsection

@section('content')

<div class="container">
    <h1>Broadcast Message</h1>

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Display Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display Error Message -->
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form to Broadcast Message with Attachments -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('messages.broadcast') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Destination Selection -->
                <div class="mb-3">
                    <label for="destination" class="form-label">Select Destination</label>
                    <select name="destination" id="destination" class="form-select" onchange="togglePhoneNumberField()">

                        <option value="" selected disabled>Select</option>
                        <option value="custom" {{ old('destination') === 'custom' ? 'selected' : '' }}>Custom</option>
                        <option value="1" {{ old('destination') === 'customers' ? 'selected' : '' }}>Customers</option>
                        <option value="2" {{ old('destination') === 'suppliers' ? 'selected' : '' }}>Suppliers</option>
                    </select>
                </div>

                <!-- Phone Numbers Upload -->
                <div class="mb-3" id="phoneNumbersField" style="{{ old('destination') === 'custom' ? '' : 'display:none;' }}">
                    <label for="phone_numbers" class="form-label">Phone Numbers (One per line)</label>
                    <textarea name="phone_numbers" id="phone_numbers" class="form-control" rows="5" placeholder="Enter phone numbers, one per line">{{ old('phone_numbers') }}</textarea>
                </div>

                <!-- Caption Input -->
                <div class="mb-3">
                    <label for="caption" class="form-label">Message</label>
                    <textarea name="caption" id="caption" class="form-control" rows="3" placeholder="Enter message">{{ old('caption') }}</textarea>
                </div>

                <!-- Option to Choose Attachment Type -->
                <div class="mb-3">
                    <label class="form-label">Choose Attachment Type:</label>
                    <div>
                        <input type="radio" id="image_option" name="attachment_type" value="image" onchange="toggleAttachmentFields()" {{ old('attachment_type') === 'image' ? 'checked' : '' }}>
                        <label for="image_option">Upload Image</label>
                    </div>
                    <div>
                        <input type="radio" id="file_option" name="attachment_type" value="file" onchange="toggleAttachmentFields()" {{ old('attachment_type') === 'file' ? 'checked' : '' }}>
                        <label for="file_option">Upload File</label>
                    </div>
                </div>

                <!-- Image Upload Field -->
                <div class="mb-3" id="imageUpload" style="{{ old('attachment_type') === 'image' ? '' : 'display:none;' }}">
                    <label for="image" class="form-label">Select Image</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                </div>

                <!-- File Upload Field -->
                <div class="mb-3" id="fileUpload" style="{{ old('attachment_type') === 'file' ? '' : 'display:none;' }}">
                    <label for="file" class="form-label">Select File</label>
                    <input type="file" name="file" id="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Send Broadcast</button>
            </form>
        </div>
    </div>
</div>
<script>
    function togglePhoneNumberField() {
        const destination = document.getElementById('destination').value;
        const phoneNumbersField = document.getElementById('phoneNumbersField');

        // Show or hide phone number field based on selected destination
        if (destination === 'custom') {
            phoneNumbersField.style.display = 'block';
        } else {
            phoneNumbersField.style.display = 'none';
        }
    }

    function toggleAttachmentFields() {
        const imageUpload = document.getElementById('imageUpload');
        const fileUpload = document.getElementById('fileUpload');

        if (document.getElementById('image_option').checked) {
            imageUpload.style.display = 'block';
            fileUpload.style.display = 'none';
        } else if (document.getElementById('file_option').checked) {
            imageUpload.style.display = 'none';
            fileUpload.style.display = 'block';
        }
    }

    document.getElementById('image').addEventListener('change', function (e) {
        const [file] = e.target.files;
        if (file) {
            const imgPreview = document.createElement('img');
            imgPreview.src = URL.createObjectURL(file);
            imgPreview.style.maxWidth = '200px';
            imgPreview.style.marginTop = '10px';
            document.getElementById('image').parentNode.appendChild(imgPreview);
        }
    });
</script>
@endsection



