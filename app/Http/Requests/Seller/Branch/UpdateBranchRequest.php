<?php

namespace App\Http\Requests\Seller\Branch;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone' => 'required',
            'delivery_cost' => 'required',
            'delivery_period' => 'required',
            'delivery_distance' => 'required',
            'location.lat' => 'required',
            'location.long' => 'required',
        ];
    }
}
