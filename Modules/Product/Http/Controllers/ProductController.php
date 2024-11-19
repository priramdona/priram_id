<?php

namespace Modules\Product\Http\Controllers;

use App\Models\Business;
use Modules\Product\DataTables\ProductDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Requests\StoreProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Upload\Entities\Upload;
use Milon\Barcode\Facades\DNS1DFacade;

class ProductController extends Controller
{

    public function generateUniqueBarcode()
    {
        do {
            // Generate random 12 digits for EAN-13 (first digit for country code can be set as desired)
            $barcode = mt_rand(100000000000, 999999999999);

            // Check if the generated barcode already exists in product_code column
            $exists = Product::where('product_code', $barcode)->exists();
        } while ($exists);

        // Return barcode HTML and the code itself
        $barcodeHtml = DNS1DFacade::getBarcodeHTML($barcode, 'EAN13');

        return response()->json([
            'barcode' => $barcode,
            'barcodeHtml' => $barcodeHtml
        ]);
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
        $arrayRequestValue = $request->except('document');
        $arrayRequestValue['business_id'] = $user->business_id;

        $product = Product::create($arrayRequestValue);

        if ($request->has('document')) {
            foreach ($request->input('document', []) as $file) {
                $product->addMedia(Storage::path('temp/dropzone/' . $file))->toMediaCollection('images');
            }
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
            $product->update($request->except('document'));

            if ($request->has('document')) {
                if (count($product->getMedia('images')) > 0) {
                    foreach ($product->getMedia('images') as $media) {
                        if (!in_array($media->file_name, $request->input('document', []))) {
                            $media->delete();
                        }
                    }
                }

                $media = $product->getMedia('images')->pluck('file_name')->toArray();

                foreach ($request->input('document', []) as $file) {
                    if (count($media) === 0 || !in_array($file, $media)) {
                        $product->addMedia(Storage::path('temp/dropzone/' . $file))->toMediaCollection('images');
                    }
                }
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
