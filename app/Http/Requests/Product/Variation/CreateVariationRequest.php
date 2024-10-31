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
        ],400);
        throw new HttpResponseException($response);
    }
    public function messages()
    {
        return [
            'variations.*.price.required' => 'Product price is required',
            'variations.*.stock_qty.required' => 'Product stock quantity is required',
            'variations.*.stock_qty.numeric' =>  'Product stock quantity  must be a type of number',
        ];
    }
}
