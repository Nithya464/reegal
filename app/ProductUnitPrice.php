<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUnitPrice extends Model
{
    use HasFactory;

    protected $table="product_unit_price";
    protected $fillable=['product_id', 'unit', 'qty', 'cost_price', 'wh_min_price', 'min_retail_price', 'base_price', 'rack', 'section', 'row', 'box_no', 'create', 'is_default', 'is_free', 'status']; 

    function scopeActive($query) {
        return $query->where('status','1');
    }
}
