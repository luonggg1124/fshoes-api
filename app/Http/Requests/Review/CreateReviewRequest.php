<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateReviewRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'text' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
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
            'product_id.required' => __('messages.create_review_request.product_id.required'),
            'product_id.exists' => __('messages.create_review_request.product_id.exists'),


            'title.required' => __('messages.create_review_request.title.required'),
            'title.string' => __('messages.create_review_request.title.string'),
            'title.max' => __('messages.create_review_request.title.max'),

            'text.required' => __('messages.create_review_request.text.required'),
            'text.string' => __('messages.create_review_request.text.string'),

            'rating.required' => __('messages.create_review_request.rating.required'),
            'rating.integer' => __('messages.create_review_request.rating.integer'),
            'rating.min' => __('messages.create_review_request.rating.min'),
            'rating.max' => __('messages.create_review_request.rating.max'),
        ];
    }
}
