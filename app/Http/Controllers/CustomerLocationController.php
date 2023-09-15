<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Route;
use App\RouteCustomer;
use App\State;
use App\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;


class CustomerLocationController extends Controller
{
    public function index(){
        if (!auth()->user()->can('customer_location.view')){
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $created_by = request()->session()->get('user.id');
            $customers = Contact::where('type','customer');
            return Datatables::of($customers)
                ->editColumn('id', function ($row) {
                  return str_pad($row->id, 4, '0', STR_PAD_LEFT);
                  
                })
                ->addColumn('sales_person', function ($row) {
                         $customerRoute=RouteCustomer::where('contact_id',$row->id)->first(); 
                         $route_id=0;
                         if($customerRoute){
                            $route_id=$customerRoute->route_id;
                         }
                         $route=Route::where('id',$route_id)->first();
                         $sales_person_id=0;
                         if($route){
                            $sales_person_id=$route->sales_person_id;
                         }
                         $user=User::where('id',$sales_person_id)->first();
                         $salesName="";
                         if($user){
                            $salesName=$user->username;
                         }
                       return  $salesName; 

                  })
                  ->addColumn('route_name', function ($row) {
                    $customerRoute=RouteCustomer::where('contact_id',$row->id)->first(); 
                    $route_id=0;
                    if($customerRoute){
                       $route_id=$customerRoute->route_id;
                    }
                    $route=Route::where('id',$route_id)->first();
                    $routeName="";
                    if($route){
                       $routeName=$route->name;
                    }
                    
                    return  $routeName; 

                    })
                    ->addColumn('full_address', function ($row) {
                        $full_address="";
                        if($row->address_line_1 !=""){
                            $full_address.=$row->address_line_1;
                        }
                        if($row->address_line_2 !=""){
                            $full_address.=",".$row->address_line_2;
                        }
                        if($row->city !=""){
                            $full_address.=",".$row->city;
                        }
                        if($row->zip_code !=""){
                            $full_address.="-".$row->zip_code;
                        }

                        return $full_address;
                    })
                    ->addColumn('last_order_date', function ($row) {
                        return "-";
                    })
                 

                ->rawColumns(['sales_person','route_name','full_address'])
                ->make(true);
        }

        return view('customer_location.index');
    }
}