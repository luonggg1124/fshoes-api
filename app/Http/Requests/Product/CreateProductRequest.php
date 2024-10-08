<?php

namespace App\Http\Requests\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'price' => 'required',
            'description' => 'nullable',
            'short_description' => 'nullable',
            'image_url' => 'required|string',
            'stock_qty' => 'required|numeric',
            'images' => 'nullable|array',
            'categories' => 'nullable|array',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'errors' => $errors->messages()
        ],400);
        throw new HttpResponseException($response);
    }
    public function messages()
    {
        return [
            'name.required' => 'Product name is required',
            'name.string' => 'Product name must be a type of string',
            'name.max' => 'Product name is too long,255 characters is maximum',


            'price.required' => 'Product price is required',

            'stock_qty.required' => 'Product stock quantity is required',
            'stock_qty.numeric' =>  'Product stock quantity  must be a type of number',

            'image_url.required' => 'Product image is required',
            'image_url.string' => 'Product image not found.Try again!',


        ];
    }
}
