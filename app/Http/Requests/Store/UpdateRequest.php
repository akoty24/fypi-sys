<?php

namespace App\Http\Requests\Store;

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
            'name_ar' => 'sometimes|required|string|max:255',
            'name_en' => 'sometimes|required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'sometimes|required|string|max:255',
            'tax_registration_number' => 'sometimes|required|integer',
            'open_at' => 'sometimes|required|date',
            'close_at' => 'sometimes|required|date',
            'status' => 'sometimes|required|boolean',
//            'user_id' => 'sometimes|required|exists:users,id'
        ];
    }
}
