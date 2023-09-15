<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table="cars";
    protected $fillable=['car_nick_name', 'years', 'exp_date', 'make_by', 'model', 'vin_no', 'licence_plate', 'start_mileage', 'load_order_type', 'description', 'status','created_by','deleted_by'];
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

   
}
