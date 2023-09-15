<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;


class Zipcode extends Model
{
    use HasFactory,SoftDeletes;
    protected $table='zipcodes';
    protected $fillable=['state_id', 'city_id', 'zipcode', 'status', 'created_by', 'deleted_by'];

    public function rules($type='create',$state_id,$city_id,$new_zipcode=null){
        switch($type){
            case('create'):
            $data = [
                'state_id'=>'required',
                'city_id'=>'required',
                'zipcode' => 'required|unique:zipcodes,zipcode,NULL,id,state_id,' . $state_id . '|unique:zipcodes,zipcode,NULL,id,city_id,' . $city_id,
            ];
            break;

            case('update'):
            $data = [
                'state_id'=>'required',
                'city_id'=>'required',
                'zipcode'=>['required',
                    Rule::unique('zipcodes')->where(function ($query) use ($state_id, $city_id, $new_zipcode) {
                        return $query->where('state_id', $state_id)
                                    ->where('city_id', $city_id)
                                    ->where('zipcode', $new_zipcode);
                    }),
                ],
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
                'state_id.required'=>'Please Select State',
                'city_id.required'=>'Please Select City',
                'zipcode.required'=>'Please Enter Zipcode',
            ];
            break;
            case('update'):
            $data = [
                'state_id.required'=>'Please Select State',
                'city_id.required'=>'Please Select City',
                'zipcode.required'=>'Please Enter Zipcode',
            ];
            break;
            default:
            $data = [];
            break;

        }
        
        return $data;
    }

    public function prepareCreateData($inputs){
        $data['state_id']=$inputs['state_id'];
        $data['city_id']=$inputs['city_id'];
        $data['zipcode']=$inputs['zipcode'];
        $data['status']=$inputs['status'];
        $data['created_by']=request()->session()->get('user.id');
        return $data;
    }

    public function prepareUpdateData($inputs, $state){
        $data = [];
        $data['state_id']=$this->arrayGet('state_id', $inputs, $state->state_name);
        $data['city_id']=$this->arrayGet('city_id', $inputs, $state->abbreviation);
        $data['zipcode']=$this->arrayGet('zipcode', $inputs, $state->abbreviation);
        $data['status']=$this->arrayGet('status', $inputs, $state->status);;
        return $data;
    }

    public function arrayGet(string $key, array $arr, $default = null)
    {
        if (is_array($arr) && array_key_exists($key, $arr) && !empty($arr[$key])) {
            return $arr[$key];
        }
        return $default;
    }

   

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    
}
