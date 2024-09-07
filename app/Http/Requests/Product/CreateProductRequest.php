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
            'sale_price' => 'required',
            'is_sale' => 'nullable',
            'description' => 'nullable',
            'short_description' => 'nullable',
            'sku' => 'nullable|string|unique:products,sku',
            'status' => 'nullable',
            'stock_qty' => 'required|numeric',
            'qty_sold' => 'nullable|numeric',
            'main_image' => 'required|image|mimes:mimes:jpg,jpeg,png,gif,svg|max:2048',
            'images' => 'nullable|array',
            'variations' => 'nullable|array',
            'variations.*.description' => 'nullable',
            'variations.*.short_description' => 'nullable',
            'variations.*.is_sale' => 'nullable',
            'variations.*.price' => 'required|numeric|max:1000000000',
            'variations.*.sale_price' => 'required|numeric|max:1000000000',
            'variations.*.stock_qty' => 'required|numeric',
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

            'slug.required' => 'Product slug is required',
            'slug.string' => 'Product slug must be a type of string',
            'slug.unique' => 'The slug have existed in the system!',
            
            'price.required' => 'Product price is required',
            'sale_price.required' => 'Product price is required',

            'sku.string' =>  'Product sku must be a type of string',
            'sku.unique' => 'The sku have existed in the system!',

            'stock_qty.required' => 'Product stock quantity is required',
            'stock_qty.numeric' =>  'Product stock quantity  must be a type of number',

            'main_image.required' => 'Product main image is required',
            'main_image.image' => 'Product main image must be a type of image',
            'main_image.mimes' => 'Product main image must be a type of jpg,jpeg,png,gif,svg',
            
            'variations.*.price' => 'Variation price is required',
            'variations.*.sale_price' => 'Variation sale price is required',


        ];
    }
}
