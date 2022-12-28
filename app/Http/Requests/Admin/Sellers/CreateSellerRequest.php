<?php

namespace App\Http\Requests\Admin\Sellers;

use Illuminate\Foundation\Http\FormRequest;

class CreateSellerRequest extends FormRequest
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
            'email' => 'required|email|unique:sellers,email,' . $this->seller->id,
            'phone' => 'required|string|unique:sellers,phone,' . $this->seller->id,
            'approved' => 'required|boolean'
        ];
    }
}
