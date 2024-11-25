<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\Auth;

class SearchProduct extends Component
{

    public $query;
    public $search_results;
    public $how_many;
    public bool $camera = false; // Default kamera tertutup

    public function mount() {
        $this->query = '';
        $this->how_many = 5;
        $this->search_results = Collection::empty();
    }

    public function render() {
        return view('livewire.search-product');
    }
    public function toggleCamera()
    {
        $this->camera = !$this->camera;
    }
    public function updatedQuery() {
        $this->search_results = Product::where('business_id',Auth::user()->business_id)
            ->where('is_showlist', true)
            ->where(
                fn ($query) => $query
                ->where('product_name', 'like', '%' . $this->query . '%')
                ->orWhere('product_code', 'like', '%' . $this->query . '%')
            )
             ->take($this->how_many)->get();
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

    public function selectProduct($productData) {
        // dd($productData);
        $product = Product::find($productData['id']);
        // dd($product->image_url);
        $this->dispatch('productSelected', $product);
        $this->dispatch('productCheckSelected', [
            'product' => $product,
            'category' => $product->category,
            'image' => $product->image_url,
        ]);
    }
}
