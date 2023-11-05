<?php

namespace App\Http\Requests\CustomerPoint;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerPointRequest extends FormRequest
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
            'customer_id' => 'required|integer|exists:customers,id',
            'user_id' => 'required|integer|exists:users,id',
            'points' => 'required|integer',
            'balance' => 'required|string',
        ];
    }
}
