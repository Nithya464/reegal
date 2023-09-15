<?php

namespace App\Http\Controllers;

use App\City;
use App\Driver;
use App\State;
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Utils\ModuleUtil;

class DriverController extends Controller
{
    public function __construct(ModuleUtil $moduleUtil)
    {
        $this->moduleUtil = $moduleUtil;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('driver.view')) {
            abort(403, 'Unauthorized action.');
        }

        // if (request()->ajax()) {
        //     $taxes = Driver::with('state', 'city')->orderBy('id', 'desc')->get();
        
        //     return DataTables::of($taxes)
        //         ->addColumn('state_name', function ($taxes) {
        //             return $taxes->state->state_name;
        //         })
        //         ->addColumn('city_name', function ($taxes) {
        //             return $taxes->city->city_name;
        //         })
        //         ->editColumn('status', function ($row) {
        //             $status=($row->status == '1') ? 'Active' : 'In-Active';
        //             $badge=($row->status == '1') ? 'success' : 'danger';
        //             return '<span class="badge badge-success">'.$status.'</span>';
        //         })
        //         ->addColumn('action', function ($row) {
        //             $actionHtml = '';
        //             $actionHtml = '';
        //             if (auth()->user()->can("state.update")) {
        //                 $actionHtml .= '<button  data-href="' . action('App\Http\Controllers\DriverController@edit', [$row->id]) . '" class="btn btn-xs btn-primary edit_driver_button" title="Edit"><i class="glyphicon glyphicon-edit"></i></button> &nbsp;';
        //             }
        //             if (auth()->user()->can("state.delete")) {
        //                 $actionHtml .= '<button data-href="' . action('App\Http\Controllers\DriverController@destroy', [$row->id]) . '" class="btn btn-xs btn-danger delete_driver_button"><i class="glyphicon glyphicon-trash"></i></button>';
        //             }
        //             return $actionHtml;
        //         })


        //         // ->removeColumn('id')
        //         ->rawColumns(['state_name','city_name','action', 'status'])
        //         ->make(true);
        // }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $user_id = request()->session()->get('user.id');

            $users = User::where('business_id', $business_id)
                        ->user()
                        ->where('is_cmmsn_agnt', 0)
                        ->role('Driver#'.$business_id)
                        ->select(['id', 'username','contact_number',
                            DB::raw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) as full_name"), 'email', 'allow_login', ]);

            
            return Datatables::of($users)
                ->editColumn('username', '{{ $username }} @if(empty($allow_login)) <span class="label bg-gray">@lang("lang_v1.login_not_allowed")</span>@endif')
                ->addColumn(
                    'contact_number',
                    function ($row) {
                        $role_name = $this->moduleUtil->getUserRoleName($row->id);
                        return $row->contact_number;
                    }
                )
                ->addColumn(
                    'action',
                    '@can("user.update")
                        <a href="{{ action(\'App\Http\Controllers\ManageUserController@edit\', [$id]) }}" class="btn btn-xs btn-primary" title="Edit"><i class="glyphicon glyphicon-edit"></i> </a>
                        &nbsp;
                    @endcan
                    @can("user.view")
                        <a href="{{ action(\'App\Http\Controllers\ManageUserController@show\', [$id]) }}" class="btn btn-xs btn-info" title="View"><i class="fa fa-eye"></i></a>
                    &nbsp;
                    @endcan
                    @can("user.delete")
                        <button data-href="{{ action(\'App\Http\Controllers\ManageUserController@destroy\', [$id]) }}" class="btn btn-xs btn-danger delete_user_button" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>
                    @endcan'
                )
                ->filterColumn('full_name', function ($query, $keyword) {
                    $query->whereRaw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) like ?", ["%{$keyword}%"]);
                })
                ->removeColumn('id')
                ->rawColumns(['action', 'username'])
                ->make(true);
        }
        return view('driver.index');

        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if (!auth()->user()->can('tax.create')) {
        //     abort(403, 'Unauthorized action.');
        // }
        $states = State::where('status', '1')->pluck('state_name', 'id')
        ->toArray();

        $cities = City::where('status', '1')->pluck('city_name', 'id')
        ->toArray();

        return view('driver.create',compact('states', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if (!auth()->user()->can('driver.add')) {
                abort(403, 'Unauthorized action.');
            }

            $inputs = $request->all();
            $tax = new Driver();
            $rules = $tax->rules('create');
            
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $createData = $tax->prepareCreateData($inputs);

            $tax = Driver::create($createData);
            $output = [
                'success' => true,
                'data' => $tax,
                'msg' => __('Driver added successfully'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }
        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // if (!auth()->user()->can('tax.update')) {
        //     abort(403, 'Unauthorized action.');
        // }

        if (request()->ajax()) {
            $created_by = request()->session()->get('user.id');
            $states = State::where('status', '1')->pluck('state_name', 'id')->toArray();
            $cities = City::where('status', '1')->pluck('city_name', 'id')->toArray();
            $driver = Driver::findOrFail($id);
            return view('driver.edit')
                ->with(compact('driver', 'states', 'cities'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('driver.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $inputs = $request->all();
                $driver = new Driver();
                $rules = $driver->rules('update', $id);
                $validator = Validator::make($inputs, $rules);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }
          
                
                $driverData = Driver::findOrFail($id);
                $updateData = $driver->prepareUpdateData($inputs, $driverData);
                $driverData->update($updateData);

                $output = [
                    'success' => true,
                    'msg' => __('Driver updated successfully'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('driver.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                Driver::destroy($id);
                $output = [
                    'success' => true,
                    'msg' => __('Driver deleted successfully'),
                ];
                
            } catch (\Exception $e) {
                \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => '__("messages.something_went_wrong")',
                ];
            }

            return $output;
        }
    }
}
