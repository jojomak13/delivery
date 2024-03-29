<?php

namespace App\Http\Requests\Seller\Bundle;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBundleRequest extends FormRequest
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
            'image' => 'nullable|image',
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
        // dd($this->available);
        $this->merge([
            'products' => collect($this->products)
                ->filter(fn($el) => $el['product'] || $el['size'])
                ->mapWithKeys(fn($el) => [$el['product']['id'] => ['size' => $el['size']]])
                ->toarray()
        ]);
    }
}
