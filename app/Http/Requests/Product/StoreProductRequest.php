<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name_ar' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif',
            'is_retail' => 'required|boolean',
            'product_code' => 'required|unique:products',
            'pricing_method' => 'required|string',
            'price' => 'required_if:pricing_method,fixed|numeric',
            'tax_group' => 'nullable|string',
            'cost_calculation_method' => 'required|string',
            'cost' => 'required_if:cost_calculation_method,component|numeric',
            'selling_unit' => 'required|string',
            'name_en' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
