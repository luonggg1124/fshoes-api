<?php

namespace App\Http\Resources\Discount;

use App\Http\Traits\ResourceSummary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    use ResourceSummary;

    public static $wrap = false;
    private string $model = 'discount';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [
            'type' => $this->type,
            'value' => $this->value,
            'start_date' => (new Carbon($this->start_date))->format('d-m-Y H:i:s'),
            'end_date' => (new Carbon($this->end_date))->format('d-m-Y H:i:s'),
            'is_active' => $this->is_active
        ];
        if ($this->includeTimes($this->model)) {
            $resource['created_at'] = (new Carbon($this->created_at))->format('d-m-Y H:i:s');
            $resource['updated_at'] = (new Carbon($this->updated_at))->format('d-m-Y H:i:s');
            $resource['deleted_at'] = (new Carbon($this->updated_at))->format('d-m-Y H:i:s');
        }
        return $resource;
    }
}
