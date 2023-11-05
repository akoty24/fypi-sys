<?php

namespace App\Http\Requests\Zone;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name_en' => 'required|unique:tables,name_en',
            'name_ar' => 'required|unique:tables,name_ar',
            'num_of_tables' => 'required|integer',
            'status' => 'required',
            'branch_id' => 'nullable|exists:branches,id',
        ];
    }
}
