<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Route;
use App\User;
use App\Utils\Util;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RouteController extends Controller
{

    /**
     * All Utils instance.
     */
    protected $moduleUtil;

    /**
     * Constructor
     *
     * @param  ProductUtils  $product
     * @return void
     */
    public function __construct(Util $moduleUtil)
    {
        $this->moduleUtil = $moduleUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        if (! auth()->user()->can('route.view')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $routes = Route::withCount('customers')->select(['id','route_id', 'name','sales_person_id','status']);

            return DataTables::of($routes)
                ->editColumn('sales_person_id', function ($row) {
                    return $row->sales->full_name;
                })
                ->addColumn('customers', function ($row) {
                    return count($row->customers);
                }) 
                ->editColumn('status', function ($row) {
                    $globlStatus = config('global.status');
                    return "<span class='badge rounded-pill {$globlStatus[$row->status]["class"]}'>{$globlStatus[$row->status]['val']}</span>";
                }) 
                ->addColumn(
                    'action',
                    '@can("route.update")
                    <button data-href="{{action(\'App\Http\Controllers\RouteController@edit\', [$id])}}" class="btn btn-xs btn-primary edit_route_button" title="Edit"><i class="glyphicon glyphicon-edit"></i></button>
                        &nbsp;
                    @endcan
                    @can("route.delete")
                        <button data-href="{{action(\'App\Http\Controllers\RouteController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_route_button" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>
                    @endcan'
                )
                ->removeColumn('id')
                ->rawColumns([3,5])
                ->make(false);
        }

        return view('route.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (! auth()->user()->can('route.create')) {
            abort(403, 'Unauthorized action.');
        }

        $quick_add = false;
        if (! empty(request()->input('quick_add'))) {
            $quick_add = true;
        }
        $business_id = $request->session()->get('user.business_id');
        $users = User::forDropdown($business_id, false, false, false, true);

        $customers = Contact::customersDropdown($business_id,false);

        return view('route.create')
                ->with(compact('quick_add','users','customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        if (! auth()->user()->can('route.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['route_id', 'name','sales_person_id','status','customer_ids']);
            $input['created_by'] = $request->session()->get('user.id');

            //Update reference count
            $ref_count = $this->moduleUtil->setAndGetReferenceCount('routes');

            if (empty($input['route_id'])) {
                //Generate reference number
                $input['route_id'] = $this->moduleUtil->generateReferenceNumber('routes', $ref_count);
            }

            //Assigned the user
            $customer_ids = [];
            if (! empty($input['customer_ids'])) {
                $customer_ids = $input['customer_ids'];
                unset($input['customer_ids']);
            }

            $route = Route::create($input);

            //Assigned the user
            if (! empty($customer_ids)) {
                $route->userHavingAccess()->sync($customer_ids);
            }

            $output = ['success' => true,
                'data' => $route,
                'msg' => __('Route added successfully'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        if (! auth()->user()->can('route.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $route = Route::find($id);
            $business_id = $request->session()->get('user.business_id');
            $users = User::forDropdown($business_id, false, false, false, true);
            $customers = Contact::customersDropdown($business_id,false);

            return view('route.edit')
                ->with(compact('route','users','customers'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
     
        if (! auth()->user()->can('route.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $input = $request->only(['name','sales_person_id','status','customer_ids']);

                $route = Route::findOrFail($id);
                $route->name = $input['name'];
                $route->sales_person_id = $input['sales_person_id'];
                $route->status = $input['status'];
                    
                $route->save();
                
                //Assigned the customers
                $customer_ids = [];
                if (! empty($input['customer_ids'])) {
                    $customer_ids = $input['customer_ids'];
                    unset($input['customer_ids']);
                }
                
                //Assigned the customers
                if (! empty($customer_ids)) {
                    $route->userHavingAccess()->sync($customer_ids);
                }
                
                $output = ['success' => true,
                    'msg' => __('Route updated successfully'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! auth()->user()->can('route.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $route = Route::findOrFail($id);
                $route->delete();

                $output = ['success' => true,
                    'msg' => __('Route deleted successfully'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }

}
