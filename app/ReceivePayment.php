<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceivePayment extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table="receive_payment";
    protected $fillable=['reference_id', 'customer_id', 'payment_id', 'due_amount', 'receive_amount', 'payment_status', 'receive_date', 'payment_mode', 'remark', 'created_by', 'status','customer_name','transaction_id','credit_amount'];
   
}
