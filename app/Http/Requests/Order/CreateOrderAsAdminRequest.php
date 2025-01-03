<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CreateOrderAsAdminRequest extends FormRequest
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
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'errors' => $errors->messages()
        ], 400);
        throw new HttpResponseException($response);
    }
    public function rules(): array
    {
        return [
            "total_amount" => "required|numeric",
            "payment_method" => "required|string",
            "payment_status" => "required|string",
            'receiver_email' => 'required|email',
            "shipping_method" => "nullable|string",
            "shipping_cost" => "required|numeric",
            "amount_collected" => "required|numeric",
            "receiver_full_name"=>"required|string",
            "phone"=>"nullable|string",
            "city"=>"nullable|string",
            "country"=>"nullable|string",
            "address"=>"nullable|string",
            "status"=>"required",
            "user_id" => "nullable|numeric",
            
        ];
    }
    public function messages()
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
            'shipping_method.string' => __('messages.create_order_request.shipping_method.string'),
            'shipping_cost.required' => __('messages.create_order_request.shipping_cost.required'),
            'shipping_cost.numeric' => __('messages.create_order_request.shipping_cost.numeric'),
            'amount_collected.required' => __('messages.create_order_request.amount_collected.required'),
            'amount_collected.numeric' => __('messages.create_order_request.amount_collected.required'),
            'receiver_full_name.string' => __('messages.create_order_request.receiver_full_name.string'),
            'phone.string' => __('messages.create_order_request.phone.string'),
            'city.string' => __('messages.create_order_request.city.string'),
            'country.string' => __('messages.create_order_request.country.required'),
            'address.string' => __('messages.create_order_request.address.string'),
            "status.required" => __('messages.create_order_request.status.required')

        ];
    }
}
