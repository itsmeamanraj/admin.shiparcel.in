<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'website_url' => ['nullable', 'url'],
            'billing_address' => ['nullable', 'string'],
            'zipcode' => ['nullable', 'string', 'max:10'],
            'city' => ['nullable', 'string'],
            'state' => ['nullable', 'string'],
            'image_url' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'status' => ['required'],
            'cod_charges' => ['required', 'integer'],
            'cod_percentage' => ['required', 'integer']
        ];
    }
}
