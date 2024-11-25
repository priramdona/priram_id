@extends('layouts.app')

@section('title', __('products.upload_product'))

{{-- @section('third_party_stylesheets')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@endsection --}}

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('products.home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('products.upload_product') }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('products.template.download') }}" class="btn btn-info mb-3">
                            {{ __('products.download_template') }} <i class="bi bi-download"></i>
                        </a>

                        <form action="{{ route('file.preview.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="file">{{ __('products.select_file') }}</label>
                                <input
                                type="file"
                                class="form-control"
                                name="file"
                                id="file"
                                accept="{{ __('products.file_accept_formats', ['formats' => '.csv, .xlsx']) }}"
                            >

                            </div>
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if(isset($dataPreview) && count($dataPreview) > 0)
                                <div class="alert alert-success">
                                    {{ __('products.preview_success') }}
                                </div>
                                <button id ="preview-button" name ="preview-button" type="submit" class="btn btn-primary" hidden>
                                    {{ __('products.upload_preview') }} <i class="bi bi-eye"></i>
                                </button>
                            @else
                            <button id ="preview-button" name ="preview-button" type="submit" class="btn btn-primary">
                                {{ __('products.upload_preview') }} <i class="bi bi-eye"></i>
                            </button>
                            @endif

                            <p><span style="font-size: 12px; color: #007bff; font-weight: bold;">{{ __('products.warning_guidance') }}</span></p>
                        </form>
                            <p><span style="font-size: 12px; color: red; font-weight: bold;">{{ __('products.warning_template_attention') }}</span><span style="font-size: 12px; color: blue; font-weight: bold;">{{ __('products.warning_template') }}</span></p>
                            <ul><strong>{{ __('products.column_order') }}</strong>
                                <li><strong>{{ __('products.product_name') }}:</strong> <span style="color: red;">{{ __('products.mandatory') }}</span> {{ __('products.product_name_guideline') }}</li>
                                <li><strong>{{ __('products.product_code') }}:</strong> <span style="color: red;">{{ __('products.mandatory') }}</span> {{ __('products.product_code_guideline') }}</li>
                                <li><strong>{{ __('products.category_name') }}:</strong> {{ __('products.category_name_guideline') }}</li>
                                <li><strong>{{ __('products.product_unit') }}:</strong> {{ __('products.product_unit_guideline') }}</li>
                                <li><strong>{{ __('products.product_quantity') }}:</strong> {{ __('products.product_quantity_guideline') }}</li>
                                <li><strong>{{ __('products.product_cost') }}:</strong> {{ __('products.product_cost_guideline') }}</li>
                                <li><strong>{{ __('products.product_price') }}:</strong> {{ __('products.product_price_guideline') }}</li>
                                <li><strong>{{ __('products.product_stock_alert') }}:</strong> {{ __('products.product_stock_alert_guideline') }}</li>
                                <li><strong>{{ __('products.product_order_tax') }}:</strong> {{ __('products.product_order_tax_guideline') }}</li>
                                <li><strong>{{ __('products.product_tax_type') }}:</strong> {{ __('products.product_tax_type_guideline') }}</li>
                                <li><strong>{{ __('products.product_note') }}:</strong> {{ __('products.product_note_guideline') }}</li>
                             </ul>
                             <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                                <table id="preview-table" class="table table-bordered" style="table-layout: auto; width: 100%;">
                                    <thead >
                                        <tr>
                                            <th style="background: #F0E68C; white-space: nowrap;">{{ __('products.action') }}</th>
                                            <th style="background: #F0E68C; white-space: nowrap;">{{ __('products.status') }}</th>
                                            <th style="background: #F0E68C; white-space: nowrap;">{{ __('products.descriptions') }}</th>
                                            <th style="background: #96cbff; white-space: nowrap;">{{ __('products.product_name') }}</th>
                                            <th style="background: #96cbff; white-space: nowrap;">{{ __('products.code') }}</th>
                                            <th style="background: #96cbff; white-space: nowrap;">{{ __('products.category_name') }}</th>
                                            <th style="background: #96cbff; white-space: nowrap;">{{ __('products.unit') }}</th>
                                            <th style="background: #96cbff; white-space: nowrap;">{{ __('products.quantity') }}</th>
                                            <th style="background: #96cbff; white-space: nowrap;">{{ __('products.cost') }}</th>
                                            <th style="background: #96cbff; white-space: nowrap;">{{ __('products.price') }}</th>
                                            <th style="background: #96cbff; white-space: nowrap;">{{ __('products.product_stock_alert') }}</th>
                                            <th style="background: #96cbff; white-space: nowrap;">{{ __('products.product_order_tax') }}</th>
                                            <th style="background: #96cbff; white-space: nowrap;">{{ __('products.product_tax_type') }}</th>
                                            <th style="background: #96cbff; white-space: nowrap;">{{ __('products.product_note') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($dataPreview) && count($dataPreview) > 0)
                                        @foreach($dataPreview as $row)
                                            <tr class="trselectedit" data-row-id="{{ $row['row_id'] }}">
                                                @if ($row['status'] == 'Oke')
                                                    <td></td>
                                                @else
                                                    <td><a onclick="generateData('{{ $row['row_id'] }}')" class="btn btn-info"><i class="bi bi-pencil"></i></a></td>
                                                @endif

                                                <td><span style="font-size: 12px; color: {{ $row['status'] == 'Oke' ? 'green' : 'red' }}; font-weight: bold;">{{ $row['status'] }}</span></td>
                                                <td style="white-space: nowrap; max-width: 200px; overflow: hidden; text-overflow: ellipsis;">{{ $row['description'] }}</td>
                                                <td style="white-space: nowrap;">{{ $row['product_name'] }}</td>
                                                <td style="white-space: nowrap;">{{ $row['product_code'] }}</td>
                                                <td style="white-space: nowrap;">{{ $row['category_name'] }}</td>
                                                <td style="white-space: nowrap;">{{ $row['product_unit'] }}</td>
                                                <td style="white-space: nowrap;">{{ $row['product_quantity'] }}</td>
                                                <td style="white-space: nowrap;">{{ $row['product_cost'] }}</td>
                                                <td style="white-space: nowrap;">{{ $row['product_price'] }}</td>
                                                <td style="white-space: nowrap;">{{ $row['product_stock_alert'] }}</td>
                                                <td style="white-space: nowrap;">{{ $row['product_order_tax'] }}</td>
                                                <td style="white-space: nowrap;">{{ $row['product_tax_type'] }}</td>
                                                <td style="white-space: nowrap;">{{ $row['product_note'] }}</td>
                                            </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                    </div>
                    <div class="card-footer">

                         <p>{{ __('products.warning_info1') }} <span style="font-size: 12px; color: red; font-weight: bold;">{{ __('products.warning_info2') }}</span>{{ __('products.warning_info3') }} </p>

                        <button type="button" id="uploaddata" name="uploaddata"  class="btn btn-info mb-3">
                            {{ __('products.upload_product') }} <i class="bi bi-upload"></i>
                        </button>
                        <br>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">{{ __('products.edit_product') }}</h5>
                    </div>
                    <div class="modal-body">
                        <form id="editForm">
                            <input type="hidden" id="row_id">
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('products.descriptions') }}</label>
                                {{-- <input type="text" class="form-control" id="description" readonly> --}}
                                <textarea name="description" id="description" rows="4 " class="form-control" readonly></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="product_name" class="form-label">{{ __('products.product_name') }}</label>
                                <input type="text" class="form-control" id="product_name">
                            </div>

                            <div class="mb-3">
                                <label for="product_code" class="form-label">{{ __('products.product_code') }}</label>
                                <div class="input-group">
                                <input type="number" class="form-control" id="product_code"
                                onkeydown="if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') { event.preventDefault(); }">

                                <button type="button" id="generate-barcode-btn" class="btn btn-primary">{{ __('products.barcode.generate') }}</button>
                                    {{-- <div id="barcode-display" class="mt-2"></div> --}}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="category_name" class="form-label">{{ __('products.category_name') }}</label>
                                <input type="text" class="form-control" id="category_name">
                            </div>

                            <div class="mb-3">
                                <label for="product_unit" class="form-label">{{ __('products.product_unit') }}</label>
                                <input type="text" class="form-control" id="product_unit">
                            </div>

                            <div class="mb-3">
                                <label for="product_quantity" class="form-label">{{ __('products.product_quantity') }}</label>
                                <input type="number" class="form-control" id="product_quantity"
                                onkeydown="if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') { event.preventDefault(); }">
                            </div>
                            <div class="mb-3">
                                <label for="product_cost" class="form-label">{{ __('products.product_cost') }}</label>
                                <input type="number" class="form-control" id="product_cost"
                                onkeydown="if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') { event.preventDefault(); }">
                            </div>
                            <div class="mb-3">
                                <label for="product_price" class="form-label">{{ __('products.product_price') }}</label>
                                <input type="number" class="form-control" id="product_price"
                                onkeydown="if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') { event.preventDefault(); }">
                            </div>
                            <div class="mb-3">
                                <label for="product_stock_alert" class="form-label">{{ __('products.product_stock_alert') }}</label>
                                <input type="number" class="form-control" id="product_stock_alert"
                                onkeydown="if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') { event.preventDefault(); }">
                            </div>
                            <div class="mb-3">
                                <label for="product_order_tax" class="form-label">{{ __('products.product_order_tax') }}</label>
                                <input type="number" class="form-control" id="product_order_tax"
                                onkeydown="if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') { event.preventDefault(); }">
                            </div>
                            <div class="mb-3">
                                <label for="product_tax_type" class="form-label">{{ __('products.product_tax_type') }}</label>
                                <select class="form-control" name="product_tax_type" id="product_tax_type">
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="product_note" class="form-label">{{ __('products.product_note') }}</label>
                                <input type="text" class="form-control" id="product_note">
                            </div>
                            <span id="error_input" style="color: red; font-weight: bold;"></span>
                            <span id="error_info" style="font-size: 12px;"></span>
                            <!-- Tambahkan field sesuai kebutuhan -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelEdit">{{ __('products.action_cancel') }}</button>
                        <button type="button" class="btn btn-primary" id="saveEdit">{{ __('products.edit_product') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')

<script>
         let originalData = {};
        const fileInput = document.getElementById('file');
        const previewButton = document.getElementById('preview-button');

        document.addEventListener('DOMContentLoaded', function () {

            fileInput.addEventListener('change', function () {

            });
        });

         $('#uploaddata').on('click', function () {
            let tableData = [];
             // Ambil elemen tbody dari tabel
            const tableBody = document.querySelector('#preview-table tbody');

            // Hitung jumlah baris dalam tbody
            const rowCount = tableBody.querySelectorAll('tr').length;

            // Cek apakah jumlah baris 0
            if (rowCount === 0) {
                  // Jika terjadi kesalahan, tampilkan pesan gagal
                  Swal.fire({
                        title: "Unggah Gagal",
                        text: "Tidak ada data yang diunggah, silahkan periksa kembali.",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        icon: 'error',
                        didOpen: () => {
                                $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                        },
                    });
                return; // Hentikan proses upload
            }


            $('#uploaddata').attr('hidden', true);
            Swal.fire({
                title: "Proses Unggah",
                text: "Sedang Unggah Data, Pastikan Aplikasi tidak Tertutup..",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
                });

            // Loop melalui setiap baris tabel
            $('#preview-table tbody tr').each(function () {
                let row = $(this);
                let rowData = {
                    row_id: row.data('row-id'),
                    status: row.find('td:nth-child(2) span').text(),
                    description: row.find('td:nth-child(3)').text(),
                    product_name: row.find('td:nth-child(4)').text(),
                    product_code: row.find('td:nth-child(5)').text(),
                    category_name: row.find('td:nth-child(6)').text(),
                    product_unit: row.find('td:nth-child(7)').text(),
                    product_quantity: row.find('td:nth-child(8)').text(),
                    product_cost: row.find('td:nth-child(9)').text(),
                    product_price: row.find('td:nth-child(10)').text(),
                    product_stock_alert: row.find('td:nth-child(11)').text(),
                    product_order_tax: row.find('td:nth-child(12)').text(),
                    product_tax_type: row.find('td:nth-child(13)').text(),
                    product_note: row.find('td:nth-child(14)').text(),
                };
                tableData.push(rowData);

            });

            // Kirim data melalui AJAX
            $.ajax({
                url: "{{ route('products.upload.process') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    tableData: tableData,
                },
                success: function (response) {

                    if (response.success) {
                        Swal.fire({
                            title: "Unggah Selesai",
                            text: "Unggah Selesai dan Berhasil, Silahkan priksa kembali informasi Produk pada Menu Produk..",
                            icon: 'success',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                                didOpen: () => {
                                        $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                                },
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = '{{ route('products.upload') }}';
                                    }
                                });
                    }
                },
                error: function (xhr) {
                    // Jika terjadi kesalahan, tampilkan pesan gagal
                    Swal.fire({
                        title: "Unggah Gagal",
                        text: "Terjadi kesalahan pada saat Unggah data, Silahkan Coba Kembali.",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        icon: 'error',
                        didOpen: () => {
                                $('.swal2-container, .swal2-popup').css('pointer-events', 'auto');
                                        },
                    });
                }
            });
        });


         document.getElementById('generate-barcode-btn').addEventListener('click', function () {
            fetchBarcode();
        });

        function fetchBarcode() {
            fetch('{{ route('generate.unique.barcode') }}')
                .then(response => response.json())
                .then(data => {
                    // Update input and display area with the generated barcode
                    document.getElementById('product_code').value = data.barcode;
                    // document.getElementById('barcode-display').innerHTML = data.barcodeHtml;
                })
                .catch(error => console.error('Error:', error));
        }

        function generateData(rowId) {
            // Simpan data asli sebelum diedit
            const row = $(`tr[data-row-id="${rowId}"]`);
            originalData = {
                row_id: rowId,
                description: row.find('td:nth-child(3)').text().trim(),
                product_name: row.find('td:nth-child(4)').text().trim(),
                product_code: row.find('td:nth-child(5)').text().trim(),
                category_name: row.find('td:nth-child(6)').text().trim(),
                product_unit: row.find('td:nth-child(7)').text().trim(),
                product_quantity: row.find('td:nth-child(8)').text().trim(),
                product_cost: row.find('td:nth-child(9)').text().trim(),
                product_price: row.find('td:nth-child(10)').text().trim(),
                product_stock_alert: row.find('td:nth-child(11)').text().trim(),
                product_order_tax: row.find('td:nth-child(12)').text().trim(),
                product_tax_type: row.find('td:nth-child(13)').text().trim(),
                product_note: row.find('td:nth-child(14)').text().trim(),
                // Tambahkan field lain sesuai kebutuhan
            };

            // Isi modal dengan data asli
            $('#row_id').val(originalData.row_id);
            $('#description').val(originalData.description);
            $('#product_name').val(originalData.product_name);
            $('#product_code').val(originalData.product_code);
            $('#category_name').val(originalData.category_name);
            $('#product_unit').val(originalData.product_unit);
            $('#product_quantity').val(originalData.product_quantity);
            $('#product_cost').val(originalData.product_cost);
            $('#product_price').val(originalData.product_price);
            $('#product_stock_alert').val(originalData.product_stock_alert);
            $('#product_order_tax').val(originalData.product_order_tax);

            $("#product_tax_type").empty();
            let exlusive =  @json(__('products.exclusive'), JSON_UNESCAPED_UNICODE);
            let inclusive =  @json(__('products.inclusive'), JSON_UNESCAPED_UNICODE);

            op =' ';
            if (originalData.product_tax_type == 'eksklusif'){
                op = '<option value="1" selected>' + exlusive.toLowerCase() + '</option>';
                op += '<option value="2">' + inclusive.toLowerCase() + '</option>';
            }else{
                op = '<option value="1">' + exlusive.toLowerCase() + '</option>';
                op += '<option value="2" selected>' + inclusive.toLowerCase() + '</option>';
            }

            $("#product_tax_type").append(op);
            $('#product_note').val(originalData.product_note);

            // Buka modal
            $('#editModal').modal('show');
        }

        $('#saveEdit').on('click', function () {
            var tax_value = 0;
            var alert_value = 0;
            const rowId = $('#row_id').val();
            const description = $('#description').val();
            const productName = $('#product_name').val();
            const product_code = $('#product_code').val();
            const category_name = $('#category_name').val();
            const product_unit = $('#product_unit').val();
            const product_quantity = $('#product_quantity').val();
            const product_cost = $('#product_cost').val();
            const product_price = $('#product_price').val();
            const product_stock_alert = $('#product_stock_alert').val();
            const product_order_tax = $('#product_order_tax').val();
            var product_tax_type = $('#product_tax_type option:selected').text();
            const product_note = $('#product_note').val();

            tax_value = product_order_tax;
            alert_value = product_stock_alert;

            document.getElementById("error_input").innerText = "";
            document.getElementById("error_info").innerText = "";

            if (product_code.length < 12){
                document.getElementById("error_input").innerText = @json(__('products.product_code'), JSON_UNESCAPED_UNICODE);
                document.getElementById("error_info").innerText = @json(__('products.product_code_guideline'), JSON_UNESCAPED_UNICODE);
                return;
            }

            if (productName.length == 0){
                document.getElementById("error_input").innerText = @json(__('products.product_name'), JSON_UNESCAPED_UNICODE);
                document.getElementById("error_info").innerText = @json(__('products.product_name_guideline'), JSON_UNESCAPED_UNICODE);
                return;
            }

            if (category_name.length == 0){
                document.getElementById("error_input").innerText = @json(__('products.category_name'), JSON_UNESCAPED_UNICODE);
                document.getElementById("error_info").innerText = @json(__('products.category_name_guideline'), JSON_UNESCAPED_UNICODE);
                return;
            }

            if (product_unit.length == 0){
                document.getElementById("error_input").innerText = @json(__('products.product_unit'), JSON_UNESCAPED_UNICODE);
                document.getElementById("error_info").innerText = @json(__('products.product_unit_guideline'), JSON_UNESCAPED_UNICODE);
                return;
            }

            if (product_quantity.length == 0 || product_quantity == 0 || product_quantity == "0"){
                document.getElementById("error_input").innerText = @json(__('products.product_quantity'), JSON_UNESCAPED_UNICODE);
                document.getElementById("error_info").innerText = @json(__('products.product_quantity_guideline'), JSON_UNESCAPED_UNICODE);
                return;
            }

            if (product_price.length == 0 || product_price == 0 || product_price == "0"){
                document.getElementById("error_input").innerText = @json(__('products.product_price'), JSON_UNESCAPED_UNICODE);
                document.getElementById("error_info").innerText = @json(__('products.product_price_guideline'), JSON_UNESCAPED_UNICODE);
                return;
            }

            if (product_cost.length == 0 || product_cost == 0 || product_cost == "0"){
                document.getElementById("error_input").innerText = @json(__('products.product_cost'), JSON_UNESCAPED_UNICODE);
                document.getElementById("error_info").innerText = @json(__('products.product_cost_guideline'), JSON_UNESCAPED_UNICODE);
                return;
            }

            if (product_stock_alert.length == 0 || product_stock_alert < 1 || product_stock_alert == "0"){
                alert_value = 0;
            }

            if (product_order_tax.length == 0 || product_order_tax < 1 || product_order_tax == "0"){
                tax_value = 0;
            }

            $.ajax({
                url: "{{ url('/products/check/data/edit') }}/",
                method: "GET",
                data: {
                    'row_id': rowId,
                    'product_name': productName,
                    'product_code': product_code,
                    'category_name': category_name,
                    'product_unit': product_unit,
                    'product_quantity': product_quantity,
                    'product_cost': product_cost,
                    'product_price': product_price,
                    'product_stock_alert': alert_value,
                    'product_order_tax': tax_value,
                    'product_tax_type': product_tax_type,
                    'product_note': product_note,
                },
                dataType: 'json',
                success: function(response) {
                    const row = $(`tr[data-row-id="${response.row_id}"]`);
                    const actionCell = row.find('td:nth-child(1)'); // Kolom untuk tombol edit
                    if (response.status === 'Oke') {
                        actionCell.html(''); // Hapus tombol edit
                    } else {
                        actionCell.html(`<a onclick="generateData('${response.row_id}')" class="btn btn-info"><i class="bi bi-pencil"></i></a>`);
                    }

                    row.find('td:nth-child(2)').html(`<span style="color: ${response.status === 'Oke' ? 'green' : 'red'}; font-weight: bold;">${response.status}</span>`);
                    row.find('td:nth-child(3)').text(response.description);
                    row.find('td:nth-child(4)').text(response.product_name);
                    row.find('td:nth-child(5)').text(response.product_code);
                    row.find('td:nth-child(6)').text(response.category_name);
                    row.find('td:nth-child(7)').text(response.product_unit);
                    row.find('td:nth-child(8)').text(response.product_quantity);
                    row.find('td:nth-child(9)').text(response.product_cost);
                    row.find('td:nth-child(10)').text(response.product_price);
                    row.find('td:nth-child(11)').text(response.product_stock_alert);
                    row.find('td:nth-child(12)').text(response.product_order_tax);
                    row.find('td:nth-child(13)').text(response.product_tax_type);
                    row.find('td:nth-child(14)').text(response.product_note);
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error); // Untuk menangani error
                }
            });

            // Tutup modal
            $('#editModal').modal('hide');
        });

        $('#cancelEdit').on('click', function () {
            // Tutup modal tanpa menyimpan perubahan
            $('#editModal').modal('hide');
        });
    </script>
@endpush
@push('page_css')
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    table, th, td {
        border: 1px solid black;
    }
    th, td {
        padding: 8px;
        text-align: left;
    }
    #preview-table td {
        white-space: nowrap;
    }
    .trselectedit:hover {
        background-color: #f1f1f1;
    }
</style>
@endpush
