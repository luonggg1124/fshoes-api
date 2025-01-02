<?php

namespace App\Http\Requests\Category;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCategoryRequest extends FormRequest
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
        $id = $this->route('id');
        return [
            'name' => 'required|string|unique:categories,name,'.$id,
            'parents' => 'array|nullable',
            'image_url' => 'nullable|string',
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
            'name.required' => __('messages.update_category_request.name.required'),
            'name.string' => __('messages.update_category_request.name.string'),
            'parents.array' => __('messages.update_category_request.parents.array'),
            'parents.nullable' => __('messages.update_category_request.parents.nullable'),
            'image_url.nullable' => __('messages.update_category_request.image_url.nullable'),
            'image_url.string' => __('messages.update_category_request.image_url.string'),
        ];
    }
}
