<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function prepareCreateData($inputs)
    {
        $data['first_name'] = $inputs['first_name'];
        $data['last_name'] = $inputs['last_name'];
        $data['email'] = $inputs['email'];
        $data['phone_number'] = $inputs['phone_number'];
        $data['state_id'] = $inputs['state_id'];
        $data['city_id'] = $inputs['city_id'];
        $data['status'] = $inputs['status'];
        $data['created_by']=request()->session()->get('user.id');
        // $data['created_by'] = request()->session()->get('user.id');
        return $data;
    }

    public function prepareUpdateData($inputs, $driver)
    {
      
        $data['first_name'] = $inputs['first_name'];
        $data['last_name'] = $inputs['last_name'];
        $data['email'] = $inputs['email'];
        $data['phone_number'] = $inputs['phone_number'];
        $data['state_id'] = $inputs['state_id'];
        $data['city_id'] = $inputs['city_id'];
        $data['status'] = $this->arrayGet('status', $inputs, $driver->status);;
        return $data;
    }

    public function arrayGet(string $key, array $arr, $default = null)
    {
        if (is_array($arr) && array_key_exists($key, $arr) && !empty($arr[$key])) {
            return $arr[$key];
        }
        return $default;
    }


    public function rules($type = 'create', $id = null)
    {

        switch ($type) {
            case ('create'):
                $data = [
                    'first_name' => 'required|string|min:2|max:255',
                    'last_name' => 'required|string|min:2|max:255',
                    'email' => 'required|email|unique:drivers,email,NULL,id,deleted_at,NULL',
                    'phone_number' => 'required|unique:drivers,phone_number,NULL,id,deleted_at,NULL',
                    'state_id' => 'required|exists:states,id,deleted_at,NULL',
                    'city_id' => 'required|exists:cities,id,deleted_at,NULL',
                    'status' => 'required|in:1,2',
                ];
                break;
                
            case ('update'):
                $data = [
                    'first_name' => 'required|string|min:2|max:255',
                    'last_name' => 'required|string|min:2|max:255',
                    'email' => 'required|email|unique:drivers,email,' . $id . ',id,deleted_at,NULL',
                    'phone_number' => 'required|numeric|unique:drivers,phone_number,' . $id . ',id,deleted_at,NULL',
                    'state_id' => 'required|exists:states,id,deleted_at,NULL',
                    'city_id' => 'required|exists:cities,id,deleted_at,NULL',
                    'status' => 'required|in:1,2',
                ];
                break;
            default:
                $data = [];
                break;
        }
        return $data;
    }
}
