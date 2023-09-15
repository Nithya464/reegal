<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WriteCheque extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function prepareCreateData($inputs)
    {   
        $data['type'] = $inputs['type'];
        $data['cheque_number'] = $inputs['cheque_number'];
        $data['employee_id'] = isset($inputs['employee_id']) ? $inputs['employee_id'] : null;
        $data['vendor_id'] = isset($inputs['vendor_id']) ? $inputs['vendor_id'] : null;
        $data['date'] = date('Y-m-d', strtotime($inputs['date']));
        $data['cheque_amount'] = $inputs['cheque_amount'];
        $data['address'] = $inputs['address'];
        $data['memo'] = $inputs['memo'];
        $data['created_by'] = request()->session()->get('user.id');
        return $data;
    }

    public function employee()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // public function vendorname()
    // {
    //     return $this->hasOne(Country::class,'id','country');
    // }



    public function prepareUpdateData($inputs, $writecheckData)
    {

        // echo "<pre>"; print_r($writecheckData);exit;
      
        $data['type'] = $inputs['type'];
        $data['cheque_number'] = $inputs['cheque_number'];
        $data['employee_id'] = isset($inputs['employee_id']) ? $inputs['employee_id'] : null;
        $data['vendor_id'] = isset($inputs['vendor_id']) ? $inputs['vendor_id'] : null;
        $data['date'] = date('Y-m-d', strtotime($inputs['date']));
        $data['cheque_amount'] = $inputs['cheque_amount'];
        $data['address'] = $inputs['address'];
        $data['memo'] = $inputs['memo'];
        return $data;
    }

    public function arrayGet(string $key, array $arr, $default = null)
    {
        if (is_array($arr) && array_key_exists($key, $arr) && !empty($arr[$key])) {
            return $arr[$key];
        }
        return $default;
    }


    public function rules($type = 'create')
    {

        switch ($type) {
            case ('create'):
                $data = [
                    'type' => 'required|string|min:2|max:255',
                    'employee_id' => 'required_without:vendor_id',
                    'vendor_id' => 'required_without:employee_id',
                    'cheque_number' => 'required',
                    'cheque_amount' => 'required|numeric',
                    'address' => 'required',
                    'date' => 'required',
                ];
                break;

            case ('update'):
                $data = [
                    'type' => 'required|string|min:2|max:255',
                    'employee_id' => 'required_without:vendor_id',
                    'vendor_id' => 'required_without:employee_id',
                    'cheque_number' => 'required',
                    'cheque_amount' => 'required|numeric',
                    'address' => 'required',
                    'date' => 'required',
                ];
                break;
            default:
                $data = [];
                break;
        }
        return $data;
    }
}
