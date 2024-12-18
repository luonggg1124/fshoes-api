<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'group' => 'nullable|integer|exists:groups,id',
            'profile' => 'nullable|array'
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
    public function messages()
    {
        return [
            'name.required' => __('messages.create_user_request.name.required'),
            'name.string' => __('messages.create_user_request.name.string'),
            'name.max' => __('messages.create_user_request.name.max'),

            'password.required' => __('messages.create_user_request.password.required'),
            'password.string' => __('messages.create_user_request.password.string'),
            'password.min' => __('messages.create_user_request.password.min'),

            'group.exists' => __('messages.create_user_request.group.exists'),
            'group.nullable' => __('messages.create_user_request.group.nullable'),
            'group.integer' => __('messages.create_user_request.group.integer'),
            'profile.nullable' => __('messages.create_user_request.profile.nullable'),
            'profile.array' => __('messages.create_user_request.profile.array'),

        ];
    }
}
