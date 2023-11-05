<?php

namespace App\Http\Requests\InventoryItem;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryItemRequest extends FormRequest
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
            'name_ar' => 'sometimes|required|string',
            'name_en' => 'nullable|string',
            'item_type' => 'nullable|integer|in:0,1',
            'code' => 'sometimes|required|string',
            'inventory_id' => 'sometimes|nullable|exists:inventories,id',
            'category_id' => 'sometimes|nullable|exists:categories,id',
            'product_id' => 'sometimes|nullable|exists:products,id',
            'supplier_id' => 'sometimes|nullable|exists:suppliers,id',
            'storage_unit' => 'sometimes|nullable|integer',
            'conversion_factor' => 'sometimes|nullable|numeric',
            'barcode' => 'sometimes|nullable|string',
            'quantity' => 'sometimes|nullable|numeric',
            'cost' => 'sometimes|nullable|numeric',
            'min_level' => 'sometimes|nullable|numeric',
            'max_level' => 'sometimes|nullable|numeric',
            'reorder_level' => 'sometimes|nullable|numeric',        ];
    }
}
