<?php

namespace App\Http\Requests\Post;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
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
            "title" => "required",
            "slug" => "required",
            "content" => "required",
            "topic_id" => "required",
            "author_id" => "required",
        ];
    }

    public function messages(): array
    {
        return [
            "title.required" => __('messages.post_request.title.required'),
            "slug.required" => __('messages.post_request.slug.required'),
            "content.required" => __('messages.post_request.content.required'),
            "topic_id.required" => __('messages.post_request.topic_id.required'),
            "author_id.required" => __('messages.post_request.author_id.required'),
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

}
