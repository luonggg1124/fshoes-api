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
            'user_id' => "required",
            "total_amount" => "required",
            "payment_method" => "required",
            "payment_status" => "required",
            "shipping_method" => "required",
            "shipping_cost" => "required",
            "amount_collected" => "required",
            "receiver_full_name"=>"required",
            "phone"=>"required",
            "city"=>"required",
            "country"=>"required",
            "address"=>"required",
            "status"=>"required"
        ];
    }
    public function messages(): array
    {
        return [
            'user_id.required' => 'User is required',
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
