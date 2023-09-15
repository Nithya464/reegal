<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="states";
    protected $fillable=['state_name', 'abbreviation', 'status', 'created_by', 'deleted_by'];

    
    public function cities()
    {
        return $this->hasMany(City::class);
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
        $data['state_name']=$inputs['state_name'];
        $data['abbreviation']=$inputs['abbreviation'];
        $data['status']=$inputs['status'];
        $data['created_by']=request()->session()->get('user.id');
        return $data;
    }

    public function prepareUpdateData($inputs, $state){
        $data = [];
        $data['state_name']=$this->arrayGet('state_name', $inputs, $state->state_name);
        $data['abbreviation']=$this->arrayGet('abbreviation', $inputs, $state->abbreviation);;
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


    public function rules($type='create'){

        switch($type){
            case('create'):
            $data = [
                'state_name'=>'required',
                'abbreviation'=>'required|max:2',
            ];
            break;

            case('update'):
            $data = [
                'state_name'=>'required',
                'abbreviation'=>'required|max:2',
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
                'state_name.required'=>'Please Enter State Name',
                'abbreviation.required'=>'Please Enter Abbreviation',
                'abbreviation.max'=>'Please Enter Abbreviation Maximum 2 Character',
            ];
            break;
            case('update'):
            $data = [
                'state_name.required'=>'Please Enter State Name',
                'abbreviation.required'=>'Please Enter Abbreviation',
                'abbreviation.max'=>'Please Enter Abbreviation Maximum 2 Character',
            ];
            break;
            default:
            $data = [];
            break;

        }
        
        return $data;
    }



}
