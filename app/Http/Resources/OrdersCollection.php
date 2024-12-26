<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;

class OrdersCollection extends JsonResource
   {
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "user_id"=>$this->user_id,
            "user"=>  UserResource::make($this->whenLoaded('user')),
            "total_amount"=> $this->total_amount,
            "payment_method"=>$this->payment_method,
            "payment_status"=>$this->payment_status,
            "shipping_method"=>$this->shipping_method,
            "shipping_cost"=>$this->shipping_cost,
            "tax_amount"=>$this->tax_amount,
            "amount_collected"=>$this->amount_collected,
            "receiver_full_name"=>$this->receiver_full_name,
            "receiver_email"=>$this->receiver_email,
            "address"=>$this->address,
            "phone"=>$this->phone,
            "city"=>$this->city,
            "country"=>$this->country,
            "voucher_id"=>  VoucherResource::make($this->whenLoaded('voucher')),
            "order_details"=> OrderDetailsCollection::collection($this->whenLoaded('orderDetails')),
            "order_history"=> $this->whenLoaded('orderHistory'),
            "status"=>$this->status,
            "reason_cancelled"=>$this->reason_cancelled,
            "reason_return"=>$this->reason_cancelled,
            "note"=>$this->note,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}

