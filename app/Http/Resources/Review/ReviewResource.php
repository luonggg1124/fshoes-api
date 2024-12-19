<?php

namespace App\Http\Resources\Review;

use App\Http\Resources\ProductResource;
use App\Http\Resources\User\UserResource;
use App\Http\Traits\ResourceSummary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ReviewResource extends JsonResource
{
    use ResourceSummary;
    public static $wrap = false; // Tắt wrapping mặc định của resource
    private $model = 'review';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [
            'id' => $this->id,
            'product' => new ProductResource($this->whenLoaded('product')),
            'user' => new UserResource($this->whenLoaded('user')),
            'title' => $this->title,
            'text' => $this->text,
            'rating' => $this->rating,
            'likes_count' => $this->likes()->count(),
            

        ];
        $user = request()->user();
        if(isset($user->id)){
            $liked = DB::table('review_like')->where('review_id',$this->id)->where('user_id',$user->id)->exists();
            if($liked){
                $resource['liked'] = true;
            }else{
                $resource['liked'] = false;
            }
        }
       
        if($this->shouldSummaryRelation($this->model))
            $resource = [
                'id' => $this->id,
                'title' => $this->title,
                'rating' => $this->rating,
                'product' => new ProductResource($this->whenLoaded('product')),
                'user' => new UserResource($this->whenLoaded('user')),
            ];
        if($this->includeTimes($this->model))
        {
            $resource['created_at'] = $this->created_at;
            $resource['updated_at'] = $this->updated_at;
            $resource['deleted_at'] = $this->deleted_at;
        }
        return $resource;
    }
}
