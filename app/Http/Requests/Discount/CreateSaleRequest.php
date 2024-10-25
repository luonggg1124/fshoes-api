<?php

namespace App\Http\Requests\Discount;

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
            'type' => ['required','string',Rule::in(['fixed','percent'])],
            'value' => 'required|number',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date',
            'products' => 'nullable|array',
            'variations' => 'nullable|array',
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
    public function messages(): array
    {
        return [
            'name.string' => 'The sale name must be a string.',
            'type.in' => 'The sale type must be fixed or percent.',
            'value.number' => 'The sale value must be a number.',
            'start_date.date' => 'The sale start date must be a date.',
            'start_date.before' => 'The sale start date must not be after the end date.',
            'end_date.date' => 'The sale end date must be a date.',

        ];
    }
}
