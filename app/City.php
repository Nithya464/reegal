<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="cities";
    protected $fillable=['state_id', 'city_name', 'status', 'created_by', 'deleted_by'];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function zipcodes()
    {
        return $this->hasMany(Zipcode::class);
    }
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function prepareCreateData($inputs){
        $data['state_id']=$inputs['state_id'];
        $data['city_name']=$inputs['city_name'];
        $data['status']=$inputs['status'];
        $data['created_by']=request()->session()->get('user.id');
        return $data;
    }

    public function prepareUpdateData($inputs, $state){
        $data = [];
        $data['state_id']=$this->arrayGet('state_id', $inputs, $state->state_name);
        $data['city_name']=$this->arrayGet('city_name', $inputs, $state->abbreviation);
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


    public function rules($type='create',$state_id,$city_id=null){

        switch($type){
            case('create'):
            $data = [
                'state_id'=>'required',
                'city_name'=>'required|unique:cities,city_name,NULL,id,state_id,' . $state_id,
            ];
            break;

            case('update'):
                $data = [
                    'state_id' => 'required',
                    'city_name' => "required|unique:cities,city_name,{$city_id},id,state_id,{$state_id}",
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
                'city_name.required'=>'Please Enter City Name',
            ];
            break;
            case('update'):
            $data = [
                'state_id.required'=>'Please Select State',
                'city_name.required'=>'Please Enter City Name',
            ];
            break;
            default:
            $data = [];
            break;

        }
        
        return $data;
    }
    
}
