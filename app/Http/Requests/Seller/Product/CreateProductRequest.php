<?php

namespace App\Http\Requests\Seller\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'image' => 'required|image',
            'size' => 'required|array|min:1',
            'size.*.id' => 'required',
            'size.*.name' => 'required',
            'size.*.price' => 'required|numeric',
            'extra' => 'required|array|min:1',
            'types' => 'nullable|array',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:255',
            'available' => 'required|boolean'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'available' => $this->available === '1'? true : false,
        ]);
    }
}
