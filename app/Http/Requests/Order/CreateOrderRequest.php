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
            'receiver_email.required' => __('messages.create_order_request.receiver_email.required'),
            'receiver_email.email' => __('messages.create_order_request.receiver_email.email'),
            'total_amount.required' => __('messages.create_order_request.total_amount.required'),
            'payment_method.required' => __('messages.create_order_request.payment_method.required'),
            'payment_status.required' => __('messages.create_order_request.payment_status.required'),
            'shipping_method.required' => __('messages.create_order_request.shipping_method.required'),
            'shipping_cost.required' => __('messages.create_order_request.shipping_cost.required'),
            'amount_collected.required' => __('messages.create_order_request.amount_collected.required'),
            "receiver_full_name.required" => __('messages.create_order_request.receiver_full_name.required'),
            "phone.required" => __('messages.create_order_request.phone.required'),
            "city.required" => __('messages.create_order_request.city.required'),
            "country.required" => __('messages.create_order_request.country.required'),
            "address.required" => __('messages.create_order_request.address.required'),
            "status.required" => __('messages.create_order_request.status.required')
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
