@php
    $businessPrefix = \App\Models\Business::where('id',Auth::user()->business_id)->first();
    $category_max_id = \Modules\Product\Entities\Category::where('business_id',$businessPrefix->id)->count('id') + 1;
    $category_code = $businessPrefix->prefix . str_pad($category_max_id, 4, '0', STR_PAD_LEFT)
@endphp
<div class="modal fade" id="categoryCreateModal" tabindex="-1" role="dialog" aria-labelledby="categoryCreateModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryCreateModalLabel">{{ __('products.create_category') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('product-categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category_code">{{ __('products.category_code') }} <span class="text-danger"></span></label>
                        <input class="form-control" type="text" name="category_code" readonly value="{{ $category_code }}">
                    </div>
                    <div class="form-group">
                        <label for="category_name">{{ __('products.category_name') }} <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="category_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('products.create_category') }} <i class="bi bi-check"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
