<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'password' => 'nullable|string|min:8|max:255|confirmed',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user()->id,
            'phone' => 'required|string|max:255|unique:users,phone,' . $this->user()->id,
            'locations' => 'required|array|min:1',
            'locations.*.lat' => 'required',
            'locations.*.lng' => 'required',
            'locations.*.name' => 'required',
        ];
    }
}
