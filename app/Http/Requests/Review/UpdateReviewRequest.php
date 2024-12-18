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

            'title.sometimes.required' => __('messages.update_review_request.title.sometimes.required'),
            'title.string' => __('messages.update_review_request.title.string'),
            'title.max' => __('messages.update_review_request.title.max'),

            'text.sometimes.required' => __('messages.update_review_request.text.sometimes.required'),
            'text.string' => __('messages.update_review_request.text.string'),

            'rating.sometimes.required' => __('messages.update_review_request.rating.sometimes.required'),
            'rating.integer' => __('messages.update_review_request.rating.integer'),
            'rating.min' => __('messages.update_review_request.rating.min'),
            'rating.max' =>  __('messages.update_review_request.rating.max'),
        ];
    }
}
