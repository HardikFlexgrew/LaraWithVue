<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
            'stock' => 'required|integer|min:0',
        ];
    }

    public function messages(){
        return [
            'title.required' => 'Title is required',
            'price.required' => 'Price is required',
            'image.required' => 'Image is required',
            'stock.required' => 'Stock is required',
            'stock.integer' => 'Stock must be a whole number'
        ];
    }

    public function attributes(){
        return [
            'stock' => 'product stock'
        ];
    }
}
