<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'reference_number' => 'required|string|max:20',
            'tax_group' => 'required|string|max:50',
            'branch_tax' => 'required|string|max:50',
            'branch_tax_number' => 'required|string|max:20',
            'beginning_work' => 'required|string',
            'end_work' => 'required|string',
            'end_day_inventory' => 'required|string',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'street_name' => 'required|string|max:100',
            'building_number' => 'required|string|max:20',
            'extension_number' => 'required|string|max:10',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'commercial_registration_number' => 'required|string|max:20',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'order_viewer_application' => 'required|string',
            'top_of_invoice' => 'required|string',
            'bottom_of_invoice' => 'required|string',
            'status' => 'required|in:0,1',
        ];
    }
}
