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
            'name' => 'required|string',
            'slug' => 'required|string|unique:categories,slug,'.$id,
            'parent_id' => 'nullable|integer|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
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
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',

            'slug.required' => 'The slug field is required.',
            'slug.string' => 'The slug must be a string.',
            'slug.unique' => 'The slug has already been taken.',

            'parent_id.integer' => 'The parent id must be an integer.',
            'parent_id.exists' => 'The parent id is not found.',

            'image.image' => 'The file must be an type of image.',
            'image.mimes' => 'The image must be a file of type: jpg, jpeg, png.',
        ];
    }
}
