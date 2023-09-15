<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStock extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="product_stocks";
    //protected $fillable = ['product_id', 'barcode', 'before_stock', 'affected_stock', 'defaul_affected_stock', 'current_stock', 'current_stock_in_defaul_unit', 'remark', 'reference_id', 'updated_by', 'created_by'];
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function defaultUnit(){
        return $this->hasOne(ProductUnitPrice::class, 'product_id', 'product_id')->where('is_default',1);
    }

}
