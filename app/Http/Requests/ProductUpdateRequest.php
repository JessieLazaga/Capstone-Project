<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $product_id = $this->route('product')->id;
        return [
            'name' => 'required|max:30|unique:products,name,'.$product_id,
            'image' => 'nullable|mimes:jpg,jpeg,png',
            'quantity' => 'required|max:11',
            'barcode' => 'required|max:12|unique:products,barcode,'.$product_id,
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ];
    }
}
