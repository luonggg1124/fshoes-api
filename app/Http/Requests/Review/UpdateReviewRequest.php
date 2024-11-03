<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateReviewRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'title' => 'sometimes|required|string|max:255',
            'text' => 'sometimes|required|string',
            'rating' => 'sometimes|required|integer|min:1|max:5',
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
            'product_id.required' => 'The product ID is required.',
            'product_id.exists' => 'The selected product does not exist in the system.',

            'title.sometimes.required' => 'Product title is required if present.',
            'title.string' => 'Product title must be a type of string.',
            'title.max' => 'Product title is too long; 255 characters is maximum.',

            'text.sometimes.required' => 'Review text is required if present.',
            'text.string' => 'Review text must be a type of string.',

            'rating.sometimes.required' => 'Rating is required if present.',
            'rating.integer' => 'Rating must be an integer.',
            'rating.min' => 'Rating must be at least 1.',
            'rating.max' => 'Rating may not be greater than 5.',
        ];
    }
}
