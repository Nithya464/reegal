<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tax extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    

    const ACTIVE_STATUS = 1;
    const DE_ACTIVE_STATUS = 2;

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    
    
    public function prepareCreateData($inputs)
    {
        $data['state_id'] = $inputs['state_id'];
        $data['name'] = $inputs['name'];
        $data['label_text'] = $inputs['label_text'];
        $data['tax'] = $inputs['tax'];
        $data['status'] = $inputs['status'];
        $data['created_by']=request()->session()->get('user.id');
        // $data['created_by'] = request()->session()->get('user.id');
        return $data;
    }

    public function prepareUpdateData($inputs, $tax)
    {
      
        $data['state_id'] = $inputs['state_id'];
        $data['name'] = $inputs['name'];
        $data['label_text'] = $inputs['label_text'];
        $data['tax'] = $inputs['tax'];
        // $data['status'] = $inputs['status'];
        $data['status'] = $this->arrayGet('status', $inputs, $tax->status);;
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
                    'name' => 'required|string|min:2|max:255',
                    'label_text' => 'required|string|max:255',
                    'tax' => 'required|numeric',
                    'state_id' => 'required|exists:states,id,deleted_at,NULL',
                    'status' => 'required|in:1,2',
                ];
                break;

            case ('update'):
                $data = [
                    'name' => 'required|string|min:2|max:255',
                    'label_text' => 'required|string|max:255',
                    'tax' => 'required|numeric',
                    'state_id' => 'required|exists:states,id,deleted_at,NULL',
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
