<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posts extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="posts";
    protected $fillable = [
        "title",
        "slug",
        "content",
        "topic_id",
        "author_id",
        "theme",
        "public_id"
    ];
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topics::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class ,'author_id' , 'id');
    }
}
