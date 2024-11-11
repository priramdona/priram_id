<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Auth;

class SearchProductMobileSelforder extends Component
{

    public $query;
    public $search_results;
    public $how_many;
    public $business;

    public function mount() {
        $this->query = '';
        $this->how_many = 5;
        $this->search_results = Collection::empty();
    }
    public function render()
    {
        return view('livewire.search-product-mobile-selforder');
    }

    public function updatedQuery() {
        // dd($this->business);
        $this->search_results = Product::where('business_id',$this->business->id)
        ->where(
                fn ($query) => $query
                ->where('product_name', 'like', '%' . $this->query . '%')
                ->orWhere('product_code', 'like', '%' . $this->query . '%')
            )->take($this->how_many)->get();

    }


    public function loadMore() {
        $this->how_many += 5;
        $this->updatedQuery();
    }

    public function resetQuery() {
        $this->query = '';
        $this->how_many = 5;
        $this->search_results = Collection::empty();
    }
    public function searchProduct($code)
    {
        // Cari produk berdasarkan kode
        $product = Product::where('product_code', $code)->first();

        if ($product) {
            $this->selectProduct($product->id); // Langsung panggil selectProduct jika produk ditemukan
        }
    }
    public function selectProduct($productData) {
        //ini dari search
        // dd('asd',$productData);
        $isDataTable = $productData['id'] ?? null;

        if($isDataTable){

            $product = Product::find($productData['id']);
        }else{
            $product = Product::find($productData);
        }
        $this->dispatch('playBeep');

        toast('Product Selected', 'success');
        $this->dispatch('productSelected', $product);
    }

}
