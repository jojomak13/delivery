<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AddCartRequest extends FormRequest
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
            'cartable_type' => 'required',
            'cartable_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'options' => 'required_unless:cartable_type,extra',
            'options.size' => 'required_if:cartable_type,product',
            'options.products' => 'required_if:cartable_type,bundle|array',
        ];
    }
}
