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
            'type.required' => 'The type field is required.',
            'type.string' => 'The type must be a string.',
            'value.required' => 'The value field is required.',
            'value.number' => __('messages.create_sale_request.value.number'),
            'is_active.nullable' => 'The is_active field is optional.',
            'is_active.boolean' => 'The is_active field must be true or false.',
            'start_date.required' => 'The start date is required.',
            'start_date.date' => __('messages.create_sale_request.start_date.date'),
            'start_date.before' => __('messages.create_sale_request.start_date.before'),
            'end_date.required' => 'The end date is required.',
            'end_date.date_format' => 'The end date must be in the format "Y-m-d H:i:s".',
            'end_date.after' => 'The end date must be after the start date.',
            'product.nullable' => 'The products field is optional.',
            'product.array' => 'The products field must be an array.',
            'variations.nullable' => 'The variations field is optional.',
            'variations.array' => 'The variations field must be an array.',
            'applyAll.nullable' => 'The applyAll field is optional.',
            'applyAll.boolean' => 'The applyAll field must be true or false.',
        ];
    }
}
