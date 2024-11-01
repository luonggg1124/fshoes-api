<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateVoucherRequest extends FormRequest
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
            "code" => "required",
            "discount" => "required",
            "date_start" => "required",
            "date_end" => "required",
            "quantity" => "required",
            "status" => "required",
        ];
    }

    public function messages(): array
    {
        return [
            "code.required" => "Voucher Code is required.",
            "discount.required" => "Discount is required.",
            "date_start.required" => "Date start is required.",
            "date_end.required" =>"Date end is required",
            "quantity.required"=>"Quantity is required",
            "status.required"=>"Status is required"
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
}
