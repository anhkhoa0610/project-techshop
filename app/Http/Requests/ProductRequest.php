<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_name' => 'required|string|max:255',
            'category_id' => 'required',
            'supplier_id' => 'required',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'volume_sold' => 'nullable|integer|min:0',
            'cover_image' => 'nullable',
            'warranty_period' => 'nullable|integer|min:0',
            'release_date' => 'nullable' // 2MB
        ];
    }

    public function messages() {
        return [];
    }
}
