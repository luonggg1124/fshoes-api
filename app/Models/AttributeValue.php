<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeValue extends Model
{
    protected $fillable = ['value','attribute_id'];
    use HasFactory, SoftDeletes;
    public function attribute():BelongsTo
    {
        return $this->belongsTo(Attribute::class,'attribute_id');
    }
    public function variations():BelongsToMany
    {
        return $this->belongsToMany(ProductVariations::class,'product_variation_attributes','attribute_value_id','variation_id');
    }

}
