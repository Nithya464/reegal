<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "vendors";
    protected $fillable = ['vendor_id', 'vendor_name', 'status', 'sub_type', 'company_name', 'contact_person', 'designation', 'cell', 'fax', 'email', 'office_1', 'other_1', 'cc_email', 'office_2', 'other_2', 'website', 'notes', 'address_1', 'address_2', 'zip_code', 'city', 'state', 'country', 'created_by', 'deleted_by'];
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    
    public function prepareCreateData($inputs)
    {
        $data['vendor_id'] = $inputs['vendor_id'];
        $data['vendor_name'] = $inputs['vendor_name'];
        $data['status'] = $inputs['status'];
        $data['sub_type'] = $inputs['sub_type'];
        $data['company_name'] = $inputs['company_name'];
        $data['contact_person'] = $inputs['contact_person'];
        $data['designation'] = $inputs['designation'];
        $data['cell'] = $inputs['cell'];
        $data['fax'] = $inputs['fax'];
        $data['email'] = $inputs['email'];
        $data['office_1'] = $inputs['office_1'];
        $data['other_1'] = $inputs['other_1'];
        $data['cc_email'] = $inputs['cc_email'];
        $data['office_2'] = $inputs['office_2'];
        $data['other_2'] = $inputs['other_2'];
        $data['website'] = $inputs['website'];
        $data['notes'] = $inputs['notes'];
        $data['address_1'] = $inputs['address_1'];
        $data['address_2'] = $inputs['address_2'];
        $data['zip_code'] = $inputs['zip_code'];
        $data['city'] = $inputs['city'];
        $data['state'] = $inputs['state'];
        $data['country'] = $inputs['country'];
        $data['created_by'] = request()->session()->get('user.id');
        return $data;
    }
    public function prepareUpdateData($inputs, $state)
    {
        $data = [];
        $data['vendor_name'] = $this->arrayGet('vendor_name', $inputs, $state->vendor_name);
        $data['status'] = $this->arrayGet('status', $inputs, $state->status);
        $data['sub_type'] = $this->arrayGet('sub_type', $inputs, $state->sub_type);
        $data['company_name'] = $this->arrayGet('company_name', $inputs, $state->company_name);
        $data['contact_person'] = $this->arrayGet('contact_person', $inputs, $state->contact_person);
        $data['designation'] = $this->arrayGet('designation', $inputs, $state->designation);
        $data['cell'] = $this->arrayGet('cell', $inputs, $state->cell);
        $data['fax'] = $this->arrayGet('fax', $inputs, $state->fax);
        $data['email'] = $this->arrayGet('email', $inputs, $state->email);
        $data['office_1'] = $this->arrayGet('office_1', $inputs, $state->office_1);
        $data['other_1'] = $this->arrayGet('other_1', $inputs, $state->other_1);
        $data['cc_email'] = $this->arrayGet('cc_email', $inputs, $state->cc_email);
        $data['office_2'] = $this->arrayGet('office_2', $inputs, $state->office_2);
        $data['other_2'] = $this->arrayGet('other_2', $inputs, $state->other_2);
        $data['website'] = $this->arrayGet('website', $inputs, $state->website);
        $data['notes'] = $this->arrayGet('notes', $inputs, $state->notes);
        $data['address_1'] = $this->arrayGet('address_1', $inputs, $state->address_1);
        $data['address_2'] = $this->arrayGet('address_2', $inputs, $state->address_2);
        $data['zip_code'] = $this->arrayGet('zip_code', $inputs, $state->zip_code);
        $data['city'] = $this->arrayGet('city', $inputs, $state->city);
        $data['state'] = $this->arrayGet('state', $inputs, $state->state);
        $data['country'] = $this->arrayGet('country', $inputs, $state->country);
        return $data;
    }
    public function arrayGet(string $key, array $arr, $default = null)
    {
        if (is_array($arr) && array_key_exists($key, $arr) && !empty($arr[$key])) {
            return $arr[$key];
        }
        return $default;
    }

    public function rules($type = 'create', $state_id)
    {

        switch ($type) {
            case ('create'):
                $data = [
                    'vendor_name' => 'required',
                    'company_name' => 'required',
                    'contact_person' => 'required',
                    'cell' => 'required',
                    'office_1' => 'required',
                    'address_1' => 'required',
                    'zip_code' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'country' => 'required',
                    'sub_type' => 'required',

                    // 'city_name'=>'required|unique:cities,city_name,NULL,id,state_id,' . $state_id,
                ];
                break;

            case ('update'):
                $data = [
                    'vendor_name' => 'required',
                    'company_name' => 'required',
                    'contact_person' => 'required',
                    'cell' => 'required',
                    'office_1' => 'required',
                    'address_1' => 'required',
                    'zip_code' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'country' => 'required',
                    'sub_type' => 'required',

                ];
                break;
            default:
                $data = [];
                break;
        }
        return $data;
    }

    // public static function allvendorDropdown($business_id, $prepend_none = true, $prepend_all = false)
    // {
    //     $all_vendors = Vendor::where('status', $business_id)->get();
    //     // ->select('id', DB::raw("CONCAT(COALESCE(surname, ''),' ',COALESCE(first_name, ''),' ',COALESCE(last_name,'')) as full_name"))
    //     $users = $all_vendors->pluck('vendor_name', 'id');

    //     //Prepend none
    //     if ($prepend_none) {
    //         $users = $users->prepend(__('lang_v1.none'), '');
    //     }

    //     //Prepend all
    //     if ($prepend_all) {
    //         $users = $users->prepend(__('lang_v1.all'), '');
    //     }

    //     return $users;
    // }

    public function rulesMessages($type = null)
    {
        switch ($type) {
            case ('create'):
                $data = [
                    // 'state_id.required'=>'Please Select State',
                    'vendor_name.required' => 'Please Enter Vendor Name',
                    'company_name.required' => 'Please Enter Company Name',
                    'contact_person.required' => 'Please Enter Contact Person Name',
                    'cell.required' => 'Please Enter Cell Number',
                    'office_1.required' => 'Please Enter Office Number',
                    'address_1.required' => 'Please Enter Address',
                    'zip_code.required' => 'Please Enter Zip Code',
                    'city.required' => 'Please Enter City',
                    'state.required' => 'Please Enter State',
                    'country.required' => 'Please Enter Country',
                    'sub_type.required' => 'Please Select Sub Type',
                ];
                break;
            case ('update'):
                $data = [
                    // 'state_id.required'=>'Please Select State',
                    'vendor_name.required' => 'Please Enter Vendor Name',
                    'company_name.required' => 'Please Enter Company Name',
                    'contact_person.required' => 'Please Enter Contact Person Name',
                    'cell.required' => 'Please Enter Cell Number',
                    'office_1.required' => 'Please Enter Office Number',
                    'address_1.required' => 'Please Enter Address',
                    'zip_code.required' => 'Please Enter Zip Code',
                    'city.required' => 'Please Enter City',
                    'state.required' => 'Please Enter State',
                    'country.required' => 'Please Enter Country',
                    'sub_type.required' => 'Please Select Sub Type',
                ];
                break;
            default:
                $data = [];
                break;

        }

        return $data;
    }

}