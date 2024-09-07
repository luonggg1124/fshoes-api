<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AttributeValue extends Model
{
    use HasFactory;
    public function attributes():BelongsTo
    {
        return $this->belongsTo(AttributeValue::class,'attribute_id');
    }
    public function productVariation():BelongsToMany
    {
        return $this->belongsToMany(ProductVariations::class,'product_variation_attributes','attribute_value_id','variation_id');
    }
    
}
