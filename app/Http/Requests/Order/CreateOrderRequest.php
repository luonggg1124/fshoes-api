<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateOrderRequest extends FormRequest
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
            "total_amount" => "required|numeric",
            "payment_method" => "required|string",
            "payment_status" => "required|string",
            'receiver_email' => 'required|email',
            "shipping_method" => "required|string",
            "shipping_cost" => "required|numeric",
            "amount_collected" => "required|numeric",
            "receiver_full_name"=>"required|string",
            "phone"=>"required|string",
            "city"=>"required|string",
            "country"=>"required|string",
            "address"=>"required|string",
            "status"=>"required"
        ];
    }
    public function messages(): array
    {
        return [
            'receiver_email.required' => 'Receiver email is required',
            'receiver_email.email' => 'Invalid Email',
            'total_amount.required' => 'Total amount is required',
            'payment_method.required' => 'Payment method is required',
            'payment_status.required' => 'Payment status is required',
            'shipping_method.required' => 'Shipping method is required',
            'shipping_cost.required' => 'Shipping cost is required',
            'amount_collected.required' => 'Amount collected is required',
            "receiver_full_name.required" => "Receiver full name is required",
            "phone.required" => "Phone is required",
            "city.required" => "City is required",
            "country.required" => "Country is required",
            "address.required" => "Address is required",
            "status.required" => "Status is required"
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
