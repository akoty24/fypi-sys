<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
            'name_en' => 'sometimes|required|string',
            'code' => 'sometimes|required|string|unique:suppliers,code,' . $this->route('id'),
            'contact_name' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'sometimes|required|email|unique:suppliers,email,' . $this->route('id'),
            'secondary_email' => 'nullable|email',
        ];
    }
}
