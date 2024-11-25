<?php

namespace Modules\Product\Http\Controllers;

use App\Models\Business;
use Modules\Product\DataTables\ProductDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Requests\StoreProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Upload\Entities\Upload;
use Milon\Barcode\Facades\DNS1DFacade;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Product\Entities\Category;
use Modules\Setting\Entities\Unit;

class ProductController extends Controller
{
    public function showUploadForm()
    {
        return view('product::products.upload'); // Pastikan file view `upload.blade.php` ada di folder `resources/views/products`
    }
    public function downloadTemplate()
    {
        return response()->download(storage_path('app/templates/product_template.xlsx'));
    }

    public function uploadProducts(Request $request)
    {
        $tableData = $request->input('tableData');
        try {
            foreach ($tableData as $data) {
                $productTaxType = null;

                if ($data['status'] == 'Oke'){
                    $dataProduct = Product::query()
                    ->where('product_code', $data['product_code'])
                    ->where('business_id', Auth::user()->business_id)
                    ->whereNull('deleted_at')
                    ->first();

                    if (strtolower($data['product_tax_type']) == 'eksklusif'){
                        $productTaxType = 1;
                    }else{
                        $productTaxType = 2;
                    }

                    if ($dataProduct){
                        $dataProduct->product_quantity = (int)$dataProduct->product_quantity + (int)$data['product_quantity'];
                        $dataProduct->product_cost = (int)$data['product_cost'];
                        $dataProduct->product_price = (int)$data['product_price'];
                        $dataProduct->product_stock_alert = (int)$data['product_stock_alert'];
                        $dataProduct->product_order_tax = (int)$data['product_order_tax'];
                        $dataProduct->product_tax_type = $productTaxType;
                        $dataProduct->product_note = $data['product_note'];
                        $dataProduct->save();
                    }else{

                        $categoryData = Category::query()
                            ->where('category_name',trim($data['category_name']))
                            ->where('business_id', Auth::user()->business_id)
                            ->whereNull('deleted_at')
                            ->first();

                        if (blank($categoryData) ){
                            $businessPrefix = Business::where('id',Auth::user()->business_id)->first();
                            $category_max_id = Category::where('business_id',$businessPrefix->id)->count('id') + 1;
                            $category_code = $businessPrefix->prefix . str_pad($category_max_id, 4, '0', STR_PAD_LEFT);

                            $categoryData = Category::create([
                                'category_code' => $category_code,
                                'category_name' => trim($data['category_name']),
                                'is_default' => false,
                                'is_showlist' => true,
                                'business_id' => Auth::user()->business_id,
                            ]);
                        }

                        $unitData = Unit::query()
                            ->whereRaw('LOWER(name) = ?', [strtolower($data['product_unit'])])
                            ->where('business_id', Auth::user()->business_id)
                            ->whereNull('deleted_at')
                            ->first();

                        if (blank($unitData) ){

                            $unitData = Unit::create([
                                'name' =>  trim($data['product_unit']),
                                'short_name' => trim($data['product_unit']),
                                'operator' => "*",
                                'operation_value' => 1,
                                'is_default' => false,
                                'is_showlist' => true,
                                'business_id' => Auth::user()->business_id,
                            ]);
                        }

                        Product::create(
                            [
                                'category_id' => $categoryData->id,
                                'product_name' => $data['product_name'],
                                'image' => null,
                                'product_code' => $data['product_code'],
                                'product_barcode_symbology' => 'EAN13',
                                'product_quantity' => (int)$data['product_quantity'],
                                'product_cost' => (int)$data['product_cost'],
                                'product_price' => (int)$data['product_price'],
                                'product_stock_alert' => (int)$data['product_stock_alert'],
                                'product_order_tax' => (int)$data['product_order_tax'],
                                'product_unit' => $unitData->name,
                                'product_tax_type' => $productTaxType,
                                'product_note' => $data['product_note'],
                                'business_id' => Auth::user()->business_id,
                                'is_default' => false,
                                'is_showlist' => true,
                                'is_action' => null,
                                'action' => null,
                            ]
                        );
                    }
                }


            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function checkDataEdit(Request $request)
    {

        $dataTable = [];
        $rowId = $request->row_id;

        $dataTable = [
            'row_id' => $rowId,
            'status' => 'Oke',
            'description' => 'Siap Unggah',
            'product_name' => $request->product_name,
            'product_code' => $request->product_code,
            'category_name' => $request->category_name,
            'category_id' => "",
            'product_unit' => $request->product_unit,
            'product_quantity' => $request->product_quantity,
            'product_cost' => $request->product_cost,
            'product_price' => $request->product_price,
            'product_stock_alert' => $request->product_stock_alert,
            'product_order_tax' => $request->product_order_tax,
            'product_tax_type' => $request->product_tax_type,
            'product_note' => $request->product_note,
        ];

        $isError = false;
        $errorValue = "";

        if (blank($dataTable['product_name']) || ($dataTable['product_name'])  == "") {
            $isError = true;
            $errorValue = 'Nama Harus Diisi';
        }


        if (!is_numeric($dataTable['product_code'])) {
            $isError = true;
            $errorValue = 'Kode harus angka';
        }else{
            if (!preg_match('/^\d{13}$/', $dataTable['product_code'])) {
                $isError = true;
                $errorValue = $errorValue . "\n" . 'Kode harus 13 digit angka.';
            }

            $data = substr($dataTable['product_code'], 0, 12);
            $providedChecksum = (int) substr($dataTable['product_code'], -1);

            $calculatedChecksum = $this->calculateEAN13Checksum($data);
            if ($providedChecksum != $calculatedChecksum) {
                $isError = true;
                $errorValue = $errorValue . "\n" . 'Format Kode Salah, Cek Kode Barcode di Produk atau buat acak, Format harus EAN13.';
            }

            if ($isError == false){
                $dataProduct = Product::query()
                ->where('product_code', $dataTable['product_code'])
                ->where('business_id', Auth::user()->business_id)
                ->whereNull('deleted_at')
                ->first();

                if ($dataProduct){
                    if(strtolower($dataProduct->product_name) != strtolower($dataTable['product_name'])){
                        $isError = true;
                        $errorValue = $errorValue . "\n" . 'Kode sudah ada tapi Nama berbeda, Nama tersimpan : '. $dataProduct->product_name;
                    }

                    $dataUnit = Unit::query()
                    ->whereRaw('LOWER(name) = ?', [strtolower($dataProduct->product_unit)])
                    ->where('business_id', Auth::user()->business_id)
                    ->whereNull('deleted_at')
                    ->first();

                    if($dataUnit){
                        if(strtolower($dataProduct->product_unit) != strtolower($dataTable['product_unit'])){
                            $isError = true;
                            $errorValue = $errorValue . "\n" . 'Kode sudah ada tapi Satuan berbeda, Satuan tersimpan : '. $dataProduct->product_unit;
                        }
                    }

                    $dataCategory = Category::query()
                    ->where('id', $dataProduct->category_id)
                    ->where('business_id', Auth::user()->business_id)
                    ->whereNull('deleted_at')
                    ->first();

                    if($dataCategory){
                        if(strtolower($dataCategory->category_name) != strtolower($dataTable['category_name'])){
                            $isError = true;
                            $errorValue = $errorValue . "\n" . 'Kode sudah ada tapi Kategori berbeda, Kategori tersimpan : '. $dataCategory->category_name;
                        }
                    }

                }
            }

        }

        if (!is_numeric($dataTable['product_quantity']) || $dataTable['product_quantity'] < 0) {
            $isError = true;
            $errorValue = $errorValue . "\n" . 'Jumlah harus angka minimal 1';
        }

        if (!is_numeric($dataTable['product_cost']) || $dataTable['product_cost'] < 0) {
            $isError = true;
            $errorValue = $errorValue . "\n" . 'Harga beli harus angka minimal 1';
        }

        if (!is_numeric($dataTable['product_price']) || $dataTable['product_price'] < 0) {
            $isError = true;
            $errorValue = $errorValue . "\n" . 'Harga jual harus angka minimal 1';
        }

        if (!is_numeric($dataTable['product_stock_alert'])) {
            $dataTable['product_stock_alert']=0;
        }

        if (!is_numeric($dataTable['product_order_tax'])) {
            $dataTable['product_order_tax']=0;
        }

        if (blank($dataTable['product_tax_type'])) {
            $dataTable['product_tax_type']="inklusif";
        }

        if($isError){
            $dataTable['status']="Gagal";
            $dataTable['description']=$errorValue;
        }

        return response()->json($dataTable);

    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'nullable|mimes:xlsx,xls,csv|max:2048',
        ]);


        $file = $request->file('file');

        if(blank($file)){
            return back()->withErrors(['file' => __('products.select_template')]);
        }
        $data = Excel::toArray([], $file);

        // Ambil sheet pertama
        $sheetData = $data[0] ?? [];
        if (empty($sheetData)) {
            return back()->withErrors(['file' => __('products.invalid_template')]);
        }

        $headerRow = $sheetData[0] ?? []; // Baris pertama
        $columnCount = count($headerRow); // Hitung jumlah kolom header

        if ($columnCount !== 11) {
            return back()->withErrors([
                'file' => __('products.invalid_template'),
            ]);
        }
        $dataTable = [];
        $dataPreview = [];
        $rowId = 0;
        $contentDatas = array_slice($sheetData, 1);
        foreach($contentDatas as $contentData){

            $rowId =+ $rowId + 1;
            $dataTable = [
                'row_id' => $rowId,
                'status' => 'Oke',
                'description' => 'Siap Unggah',
                'product_name' => $contentData[0],
                'product_code' => $contentData[1],
                'category_name' => $contentData[2],
                'category_id' => "",
                'product_unit' => $contentData[3],
                'product_quantity' => $contentData[4],
                'product_cost' => $contentData[5],
                'product_price' => $contentData[6],
                'product_stock_alert' => $contentData[7],
                'product_order_tax' => $contentData[8],
                'product_tax_type' => $contentData[9],
                'product_note' => $contentData[10],
            ];

            if (empty($dataTable['product_name']) && empty($dataTable['product_code'])) {
                break;
            }

            $isError = false;
            $errorValue = "";
            if (blank($dataTable['product_name']) || ($dataTable['product_name'])  == "") {
                $isError = true;
                $errorValue = 'Nama Harus Diisi';
            }

            if (!is_numeric($dataTable['product_code'])) {
                $isError = true;
                $errorValue = $errorValue . "\n" . 'Kode harus angka';
            }else{
                if (!preg_match('/^\d{13}$/', $dataTable['product_code'])) {
                    $isError = true;
                    $errorValue = $errorValue . "\n" . 'Kode harus 13 digit angka.';
                }

                $data = substr($dataTable['product_code'], 0, 12);
                $providedChecksum = (int) substr($dataTable['product_code'], -1);

                $calculatedChecksum = $this->calculateEAN13Checksum($data);
                if ($providedChecksum != $calculatedChecksum) {
                    $isError = true;
                    $errorValue = $errorValue . "\n" . 'Format Kode Salah, Cek Kode Barcode di Produk atau buat acak, Format harus EAN13.';
                }

                if ($isError == false){
                    $dataProduct = Product::query()
                    ->where('product_code', $dataTable['product_code'])
                    ->where('business_id', Auth::user()->business_id)
                    ->whereNull('deleted_at')
                    ->first();

                    if ($dataProduct){
                        if(strtolower($dataProduct->product_name) != strtolower($dataTable['product_name'])){
                            $isError = true;
                            $errorValue = $errorValue . "\n" . 'Kode sudah ada tapi Nama berbeda, Nama tersimpan : '. $dataProduct->product_name;
                        }


                        $dataUnit = Unit::query()
                        ->whereRaw('LOWER(name) = ?', [strtolower($dataProduct->product_unit)])
                        ->where('business_id', Auth::user()->business_id)
                        ->whereNull('deleted_at')
                        ->first();

                        if($dataUnit){
                            if(strtolower($dataProduct->product_unit) != strtolower($dataTable['product_unit'])){
                                $isError = true;
                                $errorValue = $errorValue . "\n" . 'Kode sudah ada tapi Satuan berbeda, Satuan tersimpan : '. $dataProduct->product_unit;
                            }
                        }

                        $dataCategory = Category::query()
                        ->where('id', $dataProduct->category_id)
                        ->where('business_id', Auth::user()->business_id)
                        ->whereNull('deleted_at')
                        ->first();

                        if($dataCategory){
                            if(strtolower($dataCategory->category_name) != strtolower($dataTable['category_name'])){
                                $isError = true;
                                $errorValue = $errorValue . "\n" . 'Kode sudah ada tapi Kategori berbeda, Kategori tersimpan : '. $dataCategory->category_name;
                            }
                        }
                    }
                }

            }

            if (!is_numeric($dataTable['product_quantity']) || $dataTable['product_quantity'] < 0) {
                $isError = true;
                $errorValue = $errorValue . "\n" . 'Jumlah harus angka minimal 1';
            }

            if (!is_numeric($dataTable['product_cost']) || $dataTable['product_cost'] < 0) {
                $isError = true;
                $errorValue = $errorValue . "\n" . 'Harga beli harus angka minimal 1';
            }

            if (!is_numeric($dataTable['product_price']) || $dataTable['product_price'] < 0) {
                $isError = true;
                $errorValue = $errorValue . "\n" . 'Harga jual harus angka minimal 1';
            }

            if (!is_numeric($dataTable['product_stock_alert'])) {
                $dataTable['product_stock_alert']=0;
            }

            if (!is_numeric($dataTable['product_order_tax'])) {
                $dataTable['product_order_tax']=0;
            }

            if (blank($dataTable['product_tax_type'])) {
                $dataTable['product_tax_type']="inklusif";
            }

            if($isError){
                $dataTable['status']="Gagal";
                $dataTable['description']=$errorValue;
            }

            $dataPreview[] = $dataTable;
        }

    // Proses data jika valid
    // return view('product::products.upload', compact('dataPreview'))
    //         ->with('success', __('products.preview_success'));
        return view('product::products.upload', compact('dataPreview'));
    }
        /**
     * Cek validitas barcode EAN-13.
     */
    private function calculateEAN13Checksum($data)
    {
        // Pastikan $data adalah string
        $data = (string) $data;

        $sum = 0;

        // Iterasi setiap digit dan tambahkan sesuai bobot
        for ($i = 0; $i < 12; $i++) {
            $digit = (int) $data[$i]; // Ambil digit sebagai integer
            $sum += $i % 2 === 0 ? $digit : $digit * 3; // Posisi ganjil: digit * 1, posisi genap: digit * 3
        }

        // Hitung checksum digit
        $checksum = (10 - ($sum % 10)) % 10;

        return $checksum;
    }

    public function uploadImage(Request $request, $productId)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // validasi file gambar
        ]);

        $product = Product::findOrFail($productId);

        // Proses upload gambar
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();  // beri nama file yang unik
            $request->image->move(public_path('images'), $imageName);  // simpan ke folder 'images'

            // Update path gambar di database
            $product->image = $imageName;
            $product->save();
        }

        return redirect()->back()->with('success', 'Gambar berhasil diupload.');
    }

    public function generateUniqueBarcode()
    {
        do {
            $barcode = mt_rand(100000000000, 999999999999);
            if (strlen($barcode) === 12) {
                $barcode .= $this->calculateEAN13Checksum($barcode);
            }
            $exists = Product::where('product_code', $barcode)
            ->where('business_id',Auth::user()->businessIid)
            ->exists();
        } while ($exists);

        $barcodeHtml = DNS1DFacade::getBarcodeHTML($barcode, 'EAN13');

        return response()->json([
            'barcode' => $barcode,
            'barcodeHtml' => $barcodeHtml
        ]);
    }
    public function generateBarcode()
    {
        do {
            $barcode = mt_rand(100000000000, 999999999999);
            if (strlen($barcode) === 12) {
                $barcode .= $this->calculateEAN13Checksum($barcode);
            }
            $exists = Product::where('product_code', $barcode)->exists();
        } while ($exists);

        $barcodeHtml = DNS1DFacade::getBarcodeHTML($barcode, 'EAN13');

        return  $barcode;
    }
    public function index(ProductDataTable $dataTable) {
        abort_if(Gate::denies('access_products'), 403);

        return $dataTable->render('product::products.index');
    }


    public function create() {
        abort_if(Gate::denies('create_products'), 403);

        return view('product::products.create');
    }


    public function store(StoreProductRequest $request) {
        $user = $request->user();
        $arrayRequestValue = $request->except('image');
        $arrayRequestValue['business_id'] = $user->business_id;

        $product = Product::create($arrayRequestValue);


        if ($request->hasFile('image')) {

            if ($product->image) {
                $imagePath = 'images/' . $product->image; // Pastikan path sesuai dengan yang ada di storage
                // Hapus gambar jika ada
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

            $image = $request->file('image');
            $filename = $product->id . '.' . $image->getClientOriginalExtension(); // Nama file unik
            // $imagePath = $image->storeAs('products', $filename, 'public'); // Menyimpan file di public/products
            $request->image->move(public_path('images/products'), $filename);  // simpan ke folder 'images'

            $product->update([
                'image' => 'products/' . $filename, // Update path gambar
            ]);
        }

        toast(__('controller.created'), 'success');

        return redirect()->route('products.index');
    }

    public function showsale(Product $product) {

        return view('product::products.showsale', compact('product'));
    }


    public function show(Product $product) {
        abort_if(Gate::denies('show_products'), 403);

        return view('product::products.show', compact('product'));
    }


    public function edit(Product $product) {
        abort_if(Gate::denies('edit_products'), 403);

        return view('product::products.edit', compact('product'));
    }


    public function update(UpdateProductRequest $request, Product $product) {


        if ($product->is_default == true){
            toast(__('controller.is_default_error'), 'info');
        }else{
            $product->update($request->except('image'));

            if ($request->hasFile('image')) {

                if ($product->image) {
                    $imagePath = 'images/' . $product->image; // Pastikan path sesuai dengan yang ada di storage
                    // Hapus gambar jika ada
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }

                $image = $request->file('image');
                $filename = $product->id . '.' . $image->getClientOriginalExtension(); // Nama file unik
                // $imagePath = $image->storeAs('products', $filename, 'public'); // Menyimpan file di public/products
                $request->image->move(public_path('images/products'), $filename);  // simpan ke folder 'images'

                $product->update([
                    'image' => 'products/' . $filename, // Update path gambar
                ]);
            }

            toast(__('controller.updated'), 'info');

            return redirect()->route('products.index');
        }
    }


    public function destroy(Product $product) {
        abort_if(Gate::denies('delete_products'), 403);

        $product->delete();

        toast(__('controller.deleted'), 'warning');

        return redirect()->route('products.index');
    }

}
