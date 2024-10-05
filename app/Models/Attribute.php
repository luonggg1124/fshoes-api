<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    protected $fillable = ['name'];
    use HasFactory,SoftDeletes;


    public function values():HasMany
    {
        return $this->hasMany(AttributeValue::class,'attribute_id');
    }
    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
