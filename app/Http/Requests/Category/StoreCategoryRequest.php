<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'name_ar' => 'required|string|max:255|unique:categories',
            'name_en' => 'required|string|max:255|unique:categories',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif',
            'reference_number' => 'required|integer|unique:categories',
            'branch_id' => 'nullable',
        ];
    }
}
