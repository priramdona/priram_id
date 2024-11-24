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

class ProductController extends Controller
{
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
            // Generate random 12 digits for EAN-13 (first digit for country code can be set as desired)
            $barcode = mt_rand(100000000000, 999999999999);

            // Check if the generated barcode already exists in product_code column
            $exists = Product::where('product_code', $barcode)
            ->where('business_id',Auth::user()->businessIid)
            ->exists();
        } while ($exists);
        // Return barcode HTML and the code itself
        $barcodeHtml = DNS1DFacade::getBarcodeHTML($barcode, 'EAN13');

        return response()->json([
            'barcode' => $barcode,
            'barcodeHtml' => $barcodeHtml
        ]);
    }
    public function generateBarcode()
    {
        do {
            // Generate random 12 digits for EAN-13 (first digit for country code can be set as desired)
            $barcode = mt_rand(100000000000, 999999999999);

            // Check if the generated barcode already exists in product_code column
            $exists = Product::where('product_code', $barcode)->exists();
        } while ($exists);

        // Return barcode HTML and the code itself
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
