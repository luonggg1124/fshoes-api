<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttributeValues extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='product_variation_attributes';
    protected $fillable=[
        'attribute_value_id',
        'variation_id',
    ];
}
