<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateUserRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'profile' => 'nullable|array',
            'profile.birthdate' => 'nullable|date|before:today',
            'verify_code' => 'string|nullable',
            'group_id' => 'required|integer|exists:groups,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|dimensions:min_width=100,min_height=100|max:2048',
            'is_admin' => 'nullable',
            'active' => 'nullable|boolean'
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

            'email.required' => __('messages.create_user_request.email.required'),
            'email.string' => __('messages.create_user_request.email.string'),
            'email.max' => __('messages.create_user_request.email.max'),
            'email.unique' => __('messages.create_user_request.email.unique'),
            'password.required' => __('messages.create_user_request.password.required'),
            'password.string' => __('messages.create_user_request.password.string'),
            'password.min' => __('messages.create_user_request.password.min'),

            'group.exists' => __('messages.create_user_request.group.exists'),

        ];
    }
}
