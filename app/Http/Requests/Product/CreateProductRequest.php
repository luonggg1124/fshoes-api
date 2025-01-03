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
            'import_price' => 'nullable',
            'description' => 'nullable',
            'short_description' => 'nullable',
            'image_url' => 'required|string',
            'stock_qty' => 'nullable|numeric',
            'status' => 'nullable|boolean',
            'images' => 'nullable|array',
            'categories' => 'nullable|array',
            'is_variant' => 'nullable|boolean',
            'variations' => 'nullable|array',
            'variations.*.price' => 'required|numeric',
            'variations.*.stock_qty' => 'required|numeric',
            'variations.*.status' => 'nullable|boolean',
            'variations.*.values' => 'required|array',
            'variations.*.sku' => "nullable|string"
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'errors' => $errors->messages()
        ], 400);
        throw new HttpResponseException($response);
    }
    public function messages()
    {
        return [
            'name.required' => __('messages.create_product_request.name.required'),
            'name.string' => __('messages.create_product_request.name.string'),
            'name.max' => __('messages.create_product_request.name.max'),
            'import_price.nullable' => __('messages.create_product_request.import_price.nullable'),
            'description.nullable' => __('messages.create_product_request.description.nullable'),
            'price.required' => __('messages.create_product_request.price.required'),
            'short_description.nullable' => __('messages.create_product_request.short_description.nullable'),
            'stock_qty.required' => __('messages.create_product_request.stock_qty.required'),
            'stock_qty.numeric' => __('messages.create_product_request.stock_qty.numeric'),
            'image_url.required' => __('messages.create_product_request.image_url.required'),
            'image_url.string' => __('messages.create_product_request.image_url.string'),
            'images.nullable' => __('messages.create_product_request.images.nullable'),
            'images.array' => __('messages.create_product_request.images.array'),
            'categories.nullable' => __('messages.create_product_request.categories.nullable'),
            'categories.array' => __('messages.create_product_request.categories.array'),

        ];
    }
}
