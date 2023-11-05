<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name_ar' => 'string|max:255',
            'photo' => 'string',
            'is_retail' => 'boolean',
            'product_code' => 'unique:products',
            'pricing_method' => 'string',
            'price' => 'numeric',
            'tax_group' => 'string',
            'cost_calculation_method' => 'string',
            'cost' => 'numeric',
            'selling_unit' => 'string',
            'name_en' => 'string',
            'category_id' => 'exists:categories,id',        ];
    }
}
