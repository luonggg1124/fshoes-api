<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->topic_name,
            "slug" => $this->slug,
            "content" => $this->content,
            "topic_id" =>  TopicsResource::make($this->topic),
            "author_id" =>  UserResource::make($this->user),
            "parent_topic_id" => $this->parent_topic_id,
            'deleted_at' => $this->deleted_at ? (new Carbon($this->deleted_at))->format('d-m-Y H:i:s') : null,
            'created_at' => (new Carbon($this->created_at))->format('d-m-Y H:i:s'),
            'updated_at' => (new Carbon($this->updated_at))->format('d-m-Y H:i:s')
        ];
    }
}