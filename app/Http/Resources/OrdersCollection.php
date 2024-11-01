<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            "total_amount"=> $this->total_amount,
            "payment_method"=>$this->payment_method,
            "payment_status"=>$this->payment_status,
            "shipping_method"=>$this->shipping_method,
            "shipping_cost"=>$this->shipping_cost,
            "tax_amount"=>$this->tax_amount,
            "amount_collected"=>$this->amount_collected,
            "receiver_full_name"=>$this->receiver_full_name,
            "address"=>$this->address,
            "phone"=>$this->phone,
            "city"=>$this->city,
            "country"=>$this->country,
            "voucher_id"=>  VoucherResource::make($this->whenLoaded('voucher')),
            "order_details"=> OrderDetailsCollection::make($this->whenLoaded('orderDetails')),
            "order_history"=> $this->orderHistory,
            "status"=>$this->status,
            "note"=>$this->note
        ];
    }
}

