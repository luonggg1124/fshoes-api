<?php

namespace App\Http\Requests\Product\Variation;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateVariationRequest extends FormRequest
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
            'variations' => 'array',
            'variations.*.price' => 'required',
            'variations.*.import_price' => 'nullable',
            'variations.*.sku' => 'nullable|string',
            'variations.*.description' => 'nullable',
            'variations.*.short_description' => 'nullable',
            'variations.*.status' => 'nullable',
            'variations.*.stock_qty' => 'required|numeric',
            'variations.*.attributes' => 'array|array',
            'variations.*.images' => 'nullable|array',
            'variations.*.values' => 'required|array',
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
            'variations.*.price.required' => __('messages.create_variation_request.name.string'),
            'variations.*.stock_qty.required' => __('messages.create_variation_request.variations.*.stock_qty.required'),
            'variations.*.stock_qty.numeric' => __('messages.create_variation_request.variations.*.stock_qty.numeric'),
            'variations.array' => __('messages.create_variation_request.variations.array'),
            'variations.*.import_price.nullable' => __('messages.create_variation_request.variations.*.import_price.nullable'),
            'variations.*.sku.nullable' => __('messages.create_variation_request.variations.*.sku.nullable'),
            'variations.*.sku.string' => __('messages.create_variation_request.variations.*.sku.string'),
            'variations.*.description.nullable' => __('messages.create_variation_request.variations.*.description.nullable'),
            'variations.*.short_description.nullable' => __('messages.create_variation_request.variations.*.short_description.nullable'),
            'variations.*.status.nullable' => __('messages.create_variation_request.variations.*.status.nullable'),
            'variations.*.attributes.array' => __('messages.create_variation_request.variations.*.attributes.array'),
            'variations.*.images.nullable' => __('messages.create_variation_request.variations.*.images.nullable'),
            'variations.*.images.array' => __('messages.create_variation_request.variations.*.images.array'),
            'variations.*.values.required' => __('messages.create_variation_request.variations.*.values.required'),
            'variations.*.values.array' => __('messages.create_variation_request.variations.*.values.array'),
        ];
    }
}
