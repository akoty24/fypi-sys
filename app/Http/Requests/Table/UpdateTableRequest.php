<?php

namespace App\Http\Requests\Table;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTableRequest extends FormRequest
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
        $tableId = $this->route('id');

        return [
            'table_number' => 'string|unique:tables,table_number,' . $tableId,
            'seating_capacity' => 'integer',
            'location' => 'nullable|string',
            'status' => 'in:available,reserved,occupied',
            'reservation_time' => 'nullable|date_format:Y-m-d H:i:s',
            'reservation_info' => 'nullable|json',
            'order_info' => 'nullable|json',
            'is_occupied' => 'boolean',
            'additional_notes' => 'nullable|string',
            'branch_id' => 'nullable',
        ];
    }
}
