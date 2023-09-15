<?php

namespace App\Http\Controllers;

use App\City;
use App\State;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{

    public function index()
    {
        if (!auth()->user()->can('city.view')){
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $created_by = request()->session()->get('user.id');
            $cities = City::with('state');
            return Datatables::of($cities)
                ->addColumn('state_name', function ($city) {
                    return $city->state->state_name;
                })
                ->editColumn('id', function ($row) {
                  return str_pad($row->id, 4, '0', STR_PAD_LEFT);
                  
                })
                 ->editColumn('status', function ($row) {
                    $status=($row->status=='1') ? 'Active' : 'Inactive';
                    $badge=($row->status=='1') ? 'success' : 'danger';
                    return '<span class="badge badge-success">'.$status.'</span>';
                  })
                  ->addColumn('action', function ($row) {
                        $actionHtml = '';
                        if (auth()->user()->can('city.update')) {
                           $actionHtml .= '<button  data-href="'.action('App\Http\Controllers\CityController@edit', [$row->id]).'" class="btn btn-xs btn-primary edit_city_button" title="Edit"><i class="glyphicon glyphicon-edit"></i></button> &nbsp;';
                        }
                        if (auth()->user()->can('city.delete')) {
                            $actionHtml .= '<button data-href="'.action('App\Http\Controllers\CityController@destroy', [$row->id]).'" class="btn btn-xs btn-danger delete_city_button"><i class="glyphicon glyphicon-trash"></i></button>';
                        }
                        return $actionHtml;
                    })
                ->rawColumns(['state_name','action','status'])
                ->make(true);
        }

        return view('city.index');
    }

   
    public function create()
    {
        if (! auth()->user()->can('city.create')) {
            abort(403, 'Unauthorized action.');
        }  
        $created_by = request()->session()->get('user.id');
        $states = State::where(['status' => '1'])->pluck('state_name', 'id')
        ->toArray();
        return view('city.create',compact('states'));
    }

    
    public function store(Request $request)
    {
        if (! auth()->user()->can('city.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $inputs = $request->all();
            $cities = new City();
            $state_id=($request->state_id)?$request->state_id:0;
            $rules = $cities->rules('create',$state_id);
            $messages = $cities->rulesMessages('create');
            $validator = Validator::make($inputs,$rules,$messages); 
            if($validator->fails()){
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            $createData = $cities->prepareCreateData($inputs);
           
            $city = City::create($createData);
            $output = ['success' => true,
                'data' => $city,
                'msg' => __('City Added Successfully'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }
        return $output;
    }

    public function edit($id)
    {
        if (! auth()->user()->can('city.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $created_by =request()->session()->get('user.id');
            $city = City::find($id);
            $states = State::where(['status' => '1'])->pluck('state_name', 'id')
            ->toArray();
            return view('city.edit')
                ->with(compact('city','states'));
        }
    }

   
    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('city.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
           
            $inputs = $request->all();
            $city = new City();
            $state_id=($request->state_id)?$request->state_id:0;
            $city_id=($id)?$id:0;
            $rules = $city->rules('update',$state_id,$city_id);
            $messages = $city->rulesMessages('update');
            $validator = Validator::make($inputs,$rules,$messages); 
            if($validator->fails()){
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $created_by = request()->session()->get('user.id');
            $cityData = City::findOrFail($id);
            $updateData = $city->prepareUpdateData($inputs,$cityData);
            $cityData->update($updateData);

            $output = ['success' => true,
                'msg' => __('City Updated Successfully'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

    
    public function destroy($id)
    {
        if (! auth()->user()->can('city.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $created_by = request()->session()->get('user.id');
                $state = City::findOrFail($id);
                if ($state) {
                    $state->deleted_by = request()->session()->get('user.id');
                    $state->save(); 
                    $state->delete();
                    $output = ['success' => true,
                        'msg' => __('City Deleted Successfully'),
                    ];
                }
                
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => '__("messages.something_went_wrong")',
                ];
            }

            return $output;
        }
    }
}
