<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory,SoftDeletes;

    protected $table="expenses";
    protected $fillable=['date', 'payment_method', 'payment_source_id', 'expense_category_id', 'expense_amount', 'description', 'bill_$100', 'bill_$50', 'bill_$20', 'bill_$10', 'bill_$5', 'bill_$2', 'bill_$1', 'bill_50¢', 'bill_25¢', 'bill_10¢', 'bill_5¢', 'bill_1¢', 'created_by', 'deleted_by'];
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function expense_category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function prepareCreateData($inputs){
        $data['date']=$inputs['date'];
        $data['payment_method']=$inputs['payment_method'];
        $data['payment_source_id']=$inputs['payment_source_id'];
        $data['expense_category_id']=$inputs['expense_category_id'];
        $data['expense_amount']=round($inputs['expense_amount'],2);
        $data['description']=$inputs['description'];
        $data['bill_$100']=$inputs['bill_$100'];
        $data['bill_$50']=$inputs['bill_$50'];
        $data['bill_$20']=$inputs['bill_$20'];
        $data['bill_$10']=$inputs['bill_$10'];
        $data['bill_$5']=$inputs['bill_$5'];
        $data['bill_$2']=$inputs['bill_$2'];
        $data['bill_$1']=$inputs['bill_$1'];
        $data['bill_50¢']=$inputs['bill_50¢'];
        $data['bill_25¢']=$inputs['bill_25¢'];
        $data['bill_10¢']=$inputs['bill_10¢'];
        $data['bill_5¢']=$inputs['bill_5¢'];
        $data['bill_1¢']=$inputs['bill_1¢'];
        $data['created_by']=request()->session()->get('user.id');
        return $data;
    }

    public function prepareUpdateData($inputs, $expense){
        $data = [];
        $data['date']=$this->arrayGet('date', $inputs, $expense->date);
        $data['payment_method']=$this->arrayGet('payment_method', $inputs, $expense->payment_method);
        $data['payment_source_id']=$this->arrayGet('payment_source_id', $inputs, $expense->payment_source_id);
        $data['expense_category_id']=$this->arrayGet('expense_category_id', $inputs, $expense->expense_category_id);
        $data['expense_amount']=$this->arrayGet('expense_amount', $inputs, round($expense->expense_amount));
        $data['description']=$this->arrayGet('description', $inputs, $expense->description);
        $data['bill_$100']=$this->arrayGet('bill_$100', $inputs, $expense['bill_$100']);
        $data['bill_$50']=$this->arrayGet('bill_$50', $inputs, $expense['bill_$50']);
        $data['bill_$20']=$this->arrayGet('bill_$20', $inputs, $expense['bill_$20']);
        $data['bill_$10']=$this->arrayGet('bill_$10', $inputs, $expense['bill_$10']);
        $data['bill_$5']=$this->arrayGet('bill_$5', $inputs, $expense['bill_$5']);
        $data['bill_$2']=$this->arrayGet('bill_$2', $inputs, $expense['bill_$2']);
        $data['bill_$1']=$this->arrayGet('bill_$1', $inputs, $expense['bill_$1']);
        $data['bill_50¢']=$this->arrayGet('bill_50¢', $inputs, $expense['bill_50¢']);
        $data['bill_25¢']=$this->arrayGet('bill_25¢', $inputs, $expense['bill_25¢']);
        $data['bill_10¢']=$this->arrayGet('bill_10¢', $inputs, $expense['bill_10¢']);
        $data['bill_5¢']=$this->arrayGet('bill_5¢', $inputs, $expense['bill_5¢']);
        $data['bill_1¢']=$this->arrayGet('bill_1¢', $inputs, $expense['bill_1¢']);
        return $data;
    }
    
    public function arrayGet(string $key, array $arr, $default = null)
    {
        if (is_array($arr) && array_key_exists($key, $arr) && !empty($arr[$key])) {
            return $arr[$key];
        }
        return $default;
    }


    public function rules($type='create'){

        switch($type){
            case('create'):
            $data = [
                'date'=>'required',
                'expense_category_id'=>'required',
                'description'=>'required',
                'expense_amount' => 'required|numeric|min:1',
            ];
            break;

            case('update'):
            $data = [
                'date'=>'required',
                'expense_category_id'=>'required',
                'description'=>'required',
                'expense_amount' => 'required|numeric|min:1',
            ];
            break;
            default:
            $data = [];
            break;
        }
        return $data;
    }


    public function rulesMessages($type=null){
        switch($type){
            case('create'):
            $data = [
                'date.required'=>'Please Select Date.',
                'expense_category_id.required'=>'Please Select Expense Category.',
                'description.required'=>'Please Enter Description.',
            ];
            break;
            case('update'):
            $data = [
                'date.required'=>'Please Select Date.',
                'expense_category_id.required'=>'Please Select Expense Category.',
                'description.required'=>'Please Enter Description.',
            ];
            break;
            default:
            $data = [];
            break;

        }
        
        return $data;
    }
}
