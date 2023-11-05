<?php

namespace App\Http\Requests\Zone;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name_en' => 'unique:tables,name_en,' . $this->route('table'),
            'name_ar' => 'unique:tables,name_ar,' . $this->route('table'),
            'num_of_tables' => 'integer',
            'status' => 'required',
            'branch_id' => 'nullable|exists:branches,id',
        ];
    }
}
