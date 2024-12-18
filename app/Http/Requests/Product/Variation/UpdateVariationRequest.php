<?php

namespace App\Http\Requests\Product\Variation;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateVariationRequest extends FormRequest
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
            'price' => 'required|numeric',
            'variations.*.import_price' => 'nullable',
            'sku' => 'nullable|string',
            'description' => 'nullable',
            'short_description' => 'nullable',
            'status' => 'nullable',
            'stock_qty' => 'required|numeric',
            'attributes' => 'array|array',
            'images' => 'nullable|array',
            'values' => 'required|array',
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
            'price.required' =>  __('messages.update_variation_request.price.required'),
            'stock_qty.required' => __('messages.update_variation_request.stock_qty.required'),
            'stock_qty.numeric' =>  __('messages.update_variation_request.stock_qty.numeric'),
        ];
    }
}
