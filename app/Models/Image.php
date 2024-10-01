<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Image extends Model
{
    use HasFactory;
    protected $fillable = ['image_url','public_id','alt_text'];

    public function products():BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
    public function variation():BelongsToMany
    {
        return $this->belongsToMany(ProductVariations::class);
    }
}
