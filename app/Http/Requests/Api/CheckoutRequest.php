<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'code_id' => 'nullable',
            'location.building_type' => 'required|string|in:villa,apartment,office',
            'location.street' => 'required|string|max:255',
            'location.floor_number' => 'required|string|max:255',
            'location.apartment_number' => 'required_unless:location.building_type,villa|string|max:255',
            'location.phone_number' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1024',
        ];
    }
}
