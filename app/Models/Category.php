<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Category extends Model
{
    use HasFactory,softDeletes;
    protected $fillable = ['name', 'slug', 'parent_id','image_url', 'public_id'];
    public function products():BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_id', 'product_id');
    }
    public function interestedBy():BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_interests', 'category_id', 'user_id');
    }
}
