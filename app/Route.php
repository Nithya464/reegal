<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Route extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * All user who have access to this contact
     * Applied only when selected_contacts is true for a user in
     * users table
     */
    public function userHavingAccess()
    {
        return $this->belongsToMany(\App\Contact::class, 'route_customers');
    }

    public function sales() {
        return $this->hasOne(User::class, 'id', 'sales_person_id')->select('id', DB::raw("CONCAT(COALESCE(surname, ''),' ',COALESCE(first_name, ''),' ',COALESCE(last_name,'')) as full_name"));
    }

    public function customers() {
        return $this->hasMany(RouteCustomer::class, 'route_id', 'id');
    }
}
