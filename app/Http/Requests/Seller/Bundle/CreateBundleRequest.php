<?php

namespace App\Http\Requests\Seller\Bundle;

use Illuminate\Foundation\Http\FormRequest;

class CreateBundleRequest extends FormRequest
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
            'price' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:255',
            'allowed_items' => 'required|integer',
            'available' => 'required|boolean',
            'products' => 'required|array|min:1',
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
            'products' => collect($this->products)
                ->mapWithKeys(fn($el) => [$el['product']['id'] => ['size' => $el['size']]])
                ->toarray()
        ]);
    }
}
