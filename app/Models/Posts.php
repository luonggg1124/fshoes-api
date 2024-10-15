<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        "author_id"
    ];
}
