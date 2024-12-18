<?php

namespace App\Http\Requests\Sale;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

class UpdateSaleRequest extends FormRequest
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
            'name.string' => __('messages.update_sale_request.name.string'),
            'type.in' => __('messages.update_sale_request.type.in'),
            'type.required' => ('The type field is required.'),
            'value.numeric' => __('messages.update_sale_request.value.number'),
            'value.required' => 'The value is required',
            'start_date.required' => 'The start date is required',
            'end_date.required' => 'The start date is required',
            'end_date.format' => 'The end date must be in the format.',
            'end_date_after' => 'The end date must be after the start date.',
            'start_date.date_format' => 'Invalid format date',
            'start_date.date' => __('messages.update_sale_request.start_date.date'),
            'start_date.before' => __('messages.update_sale_request.start_date.before'),
            'variations.nullable' => 'The variations field is optional.',
            'variations.array' => 'The variations field must be an array.'

        ];
    }
}
