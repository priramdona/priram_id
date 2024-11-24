<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_name' => ['required', 'string', 'max:255'],
            'product_code' => [
            'required',
            'string',
            'max:255',
        Rule::unique('products', 'product_code')
            ->ignore($this->product->id)
            ->where('business_id', Auth::user()->business_id)
            ->whereNull('deleted_at')
            ],
            'product_barcode_symbology' => ['required', 'string', 'max:255'],
            'product_unit' => ['required', 'string', 'max:255'],
            'product_quantity' => ['required', 'integer', 'min:1'],
            'product_cost' => ['required', 'numeric', 'max:2147483647'],
            'product_price' => ['required', 'numeric', 'max:2147483647'],
            'product_stock_alert' => ['required', 'numeric', 'min:0'],
            'product_order_tax' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'product_tax_type' => ['nullable', 'numeric'],
            'product_note' => ['nullable', 'string', 'max:1000'],
            'category_id' => ['required', 'string']
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('edit_products');
    }
}
