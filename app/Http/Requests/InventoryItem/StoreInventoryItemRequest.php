<?php

namespace App\Http\Requests\InventoryItem;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryItemRequest extends FormRequest
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
            'name_ar' => 'required|string',
            'name_en' => 'nullable|string',
            'item_type' => 'nullable|integer|in:0,1',
            'code' => 'required|string',
            'inventory_id' => 'nullable|exists:inventories,id',
            'category_id' => 'nullable|exists:categories,id',
            'product_id' => 'nullable|exists:products,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'storage_unit' => 'nullable|integer',
            'conversion_factor' => 'nullable|numeric',
            'barcode' => 'nullable|string',
            'quantity' => 'nullable|numeric',
            'cost' => 'nullable|numeric',
            'min_level' => 'nullable|numeric',
            'max_level' => 'nullable|numeric',
            'reorder_level' => 'nullable|numeric',
        ];
    }
}
