<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Check if the category exists
        return [
            'name_ar' => 'required|string|max:255|unique:categories,name_ar,' .  $this->route('id'),
            'name_en' => 'required|string|max:255|unique:categories,name_en,' . $this->route('id'),
            'photo' => 'required',
            'branch_id' => 'nullable',
            'reference_number' => 'required|integer|unique:categories,reference_number,' . $this->route('id'),
        ];
    }
}
