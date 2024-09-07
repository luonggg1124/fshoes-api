<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariations extends Model
{
    use HasFactory;
    protected $table = "product_variations";
    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
