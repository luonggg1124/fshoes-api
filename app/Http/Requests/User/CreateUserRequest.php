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
            'group' => 'nullable|integer|exists:groups,id',
            'profile' => 'nullable|array',
            'verify_code' => 'string|nullable',
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
            'name.required' => 'User name is required',
            'name.string' => 'Product name must be a type of string',
            'name.max' => 'Product name is too long,255 characters is maximum',

            'email.required' => 'Email is required',
            'email.string' => 'Email must be a type of string',
            'email.max' => 'Email is too long,255 characters is maximum',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.string' => 'Password must be a type of string',
            'password.min' => 'Password must be at least 6 characters',



            'group.exists' => 'Group does not exist',

        ];
    }
}
