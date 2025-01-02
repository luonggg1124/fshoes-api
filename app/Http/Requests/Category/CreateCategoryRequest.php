<?php

namespace App\Http\Requests\Category;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateCategoryRequest extends FormRequest
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
            'name' => 'required|string|unique:categories,name',
            'parents' => 'array|nullable',
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
            'name.required' => __('messages.create_category_request.name.required'),
            'name.string' => __('messages.create_category_request.name.string'),
            'parents.array' => __('messages.create_category_request.parents.array'),
            'parents.nullable' => __('messages.create_category_request.parents.nullable'),
        ];
    }
}
