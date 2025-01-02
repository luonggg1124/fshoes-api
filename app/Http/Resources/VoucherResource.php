<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'code'=>$this->code,
            'type'=>$this->type,
            'discount'=>$this->discount,
            'date_start'=>(new Carbon($this->date_start))->format('d-m-Y H:i:s'),
            'date_end'=> (new Carbon($this->date_end))->format('d-m-Y H:i:s'),
            'quantity'=>$this->quantity,
            'min_total_amount'=>$this->min_total_amount,
            'max_total_amount'=>$this->max_total_amount,
            'status'=>$this->status,
            'deleted_at' => $this->deleted_at?(new Carbon($this->deleted_at))->format('d-m-Y H:i:s') : null,
            'created_at' => (new Carbon($this->created_at))->format('d-m-Y H:i:s'),
        ];
    }
}
