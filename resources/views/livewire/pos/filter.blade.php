<div>
    <div class="form-row">
        <div class="col-md-7">
            <div class="form-group">
                <label>{{  __('sales.search_product.filter.product_category') }}</label>
                <select wire:model.live="category" class="form-control">
                    <option value="">{{  __('sales.search_product.filter.product_category_all') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label>{{  __('sales.search_product.filter.product_count') }}</label>
                <select wire:model.live="showCount" class="form-control">
                    <option value="9">9 {{  __('sales.search_product.filter.product') }}</option>
                    <option value="15">15 {{  __('sales.search_product.filter.product') }}</option>
                    <option value="21">21 {{  __('sales.search_product.filter.product') }}</option>
                    <option value="30">30 {{  __('sales.search_product.filter.product') }}</option>
                    <option value="">All {{  __('sales.search_product.filter.product') }}</option>
                </select>
            </div>
        </div>
    </div>
</div>
