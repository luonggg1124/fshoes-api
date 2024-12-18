<?php

namespace App\Http\Requests\Sale;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CreateSaleRequest extends FormRequest
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
            'name' => 'nullable|string',
            'type' => ['required', 'string', Rule::in(['fixed', 'percent'])],
            'value' => 'required|numeric',
            'is_active' => 'nullable|boolean',
            'start_date' => 'required|date_format:Y-m-d H:i:s|before:end_date',
            'end_date' => 'required|date_format:Y-m-d H:i:s|after:start_date',
            'products' => 'nullable|array',
            'variations' => 'nullable|array',
            'applyAll' => 'nullable|boolean'
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
    public function messages(): array
    {
        return [
            'name.string' => __('messages.create_sale_request.name.string'),
            'type.in' => __('messages.create_sale_request.type.in'),
            'type.required' => __('messages.create_sale_request.type.required'),
            'type.string' => __('messages.create_sale_request.type.string'),
            'value.required' => __('messages.create_sale_request.value.required'),
            'value.number' => __('messages.create_sale_request.value.number'),
            'is_active.nullable' => __('messages.create_sale_request.is_active.nullable'),
            'is_active.boolean' => __('messages.create_sale_request.is_active.boolean'),
            'start_date.required' => __('messages.create_sale_request.start_date.required'),
            'start_date.date' => __('messages.create_sale_request.start_date.date'),
            'start_date.before' => __('messages.create_sale_request.start_date.before'),
            'end_date.required' => __('messages.create_sale_request.end_date.required'),
            'end_date.date_format' => __('messages.create_sale_request.end_date.date_format'),
            'end_date.after' => __('messages.create_sale_request.end_date.after'),
            'product.nullable' => __('messages.create_sale_request.product.nullable'),
            'product.array' => __('messages.create_sale_request.product.array'),
            'variations.nullable' => __('messages.create_sale_request.variations.nullable'),
            'variations.array' => __('messages.create_sale_request.variations.array'),
            'applyAll.nullable' => __('messages.create_sale_request.applyAll.nullable'),
            'applyAll.boolean' => __('messages.create_sale_request.applyAll.boolean'),
        ];
    }
}
