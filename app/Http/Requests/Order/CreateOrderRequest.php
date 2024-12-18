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
            'total_amount.numeric' => __('messages.create_order_request.total_amount.numeric'),
            'payment_method.required' => __('messages.create_order_request.payment_method.required'),
            'payment_method.string' => __('messages.create_order_request.payment_method.string'),
            'payment_status.required' => __('messages.create_order_request.payment_status.required'),
            'payment_status.string' => __('messages.create_order_request.payment_status.string'),
            'shipping_method.required' => __('messages.create_order_request.shipping_method.required'),
            'shipping_method.string' => __('messages.create_order_request.shipping_method.string'),
            'shipping_cost.required' => __('messages.create_order_request.shipping_cost.required'),
            'shipping_cost.numeric' => __('messages.create_order_request.shipping_cost.numeric'),
            'amount_collected.required' => __('messages.create_order_request.amount_collected.required'),
            'amount_collected.numeric' => __('messages.create_order_request.amount_collected.required'),
            "receiver_full_name.required" => __('messages.create_order_request.receiver_full_name.required'),
            'receiver_full_name.string' => __('messages.create_order_request.receiver_full_name.string'),
            "phone.required" => __('messages.create_order_request.phone.required'),
            'phone.string' => __('messages.create_order_request.phone.string'),
            "city.required" => __('messages.create_order_request.city.required'),
            'city.string' => __('messages.create_order_request.city.string'),
            "country.required" => __('messages.create_order_request.country.required'),
            'country.string' => __('messages.create_order_request.country.required'),
            "address.required" => __('messages.create_order_request.country.string'),
            'address.string' => __('messages.create_order_request.address.string'),
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
