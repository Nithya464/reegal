<?php

namespace App\Http\Controllers;

use App\City;
use App\State;
use App\Zipcode;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ZipcodeController extends Controller
{
   
    public function index()
    {
        if (!auth()->user()->can('zipcode.view')){
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $created_by = request()->session()->get('user.id');
            $zipcodes = Zipcode::with(['state','city']);
           
            return Datatables::of($zipcodes)
                ->addColumn('state_name', function ($zipcode) {
                    return $zipcode->state->state_name;
                })
                ->addColumn('city_name', function ($zipcode) {
                    return $zipcode->city->city_name;
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
                        if (auth()->user()->can('zipcode.update')) {
                           $actionHtml .= '<button  data-href="'.action('App\Http\Controllers\ZipcodeController@edit', [$row->id]).'" class="btn btn-xs btn-primary edit_zipcode_button" title="Edit"><i class="glyphicon glyphicon-edit"></i></button> &nbsp;';
                        }
                        if (auth()->user()->can('zipcode.delete')) {
                            $actionHtml .= '<button data-href="'.action('App\Http\Controllers\ZipcodeController@destroy', [$row->id]).'" class="btn btn-xs btn-danger delete_zipcode_button"><i class="glyphicon glyphicon-trash"></i></button>';
                        }
                        return $actionHtml;
                    })

               
                // ->removeColumn('id')
                ->rawColumns(['state_name','action','status'])
                ->make(true);
        }

        return view('zipcode.index');
    }

    public function create()
    {
      
        if (! auth()->user()->can('zipcode.create')) {
            abort(403, 'Unauthorized action.');
        } 

        $created_by = request()->session()->get('user.id');
        $states = State::where(['status' => '1'])->pluck('state_name', 'id')->toArray();
        
        return view('zipcode.create',compact('states'));
    }

    
    public function store(Request $request)
    {
        if (! auth()->user()->can('zipcode.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $inputs = $request->all();
            $zipcode = new Zipcode();
            $state_id=($request->state_id)?$request->state_id:0;
            $city_id=($request->city_id)?$request->city_id:0;
            $rules = $zipcode->rules('create',$state_id,$city_id);
            $messages = $zipcode->rulesMessages('create');
            $validator = Validator::make($inputs,$rules,$messages); 
            if($validator->fails()){
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            $createData = $zipcode->prepareCreateData($inputs);
           
            $zipcode = Zipcode::create($createData);
            $output = ['success' => true,
                'data' => $zipcode,
                'msg' => __('Zipcode Added Successfully'),
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
        if (! auth()->user()->can('zipcode.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $created_by =request()->session()->get('user.id');
            $zipcode = Zipcode::find($id);
            $states = State::where(['status' => '1'])->pluck('state_name', 'id')->toArray();
            $cities = City::where(['status' => '1'])->pluck('city_name', 'id')->toArray();
            return view('zipcode.edit')->with(compact('zipcode','states','cities'));
        }
    }

  
    public function update(Request $request, $id)
    {
        
        if (! auth()->user()->can('zipcode.update')) {
            abort(403, 'Unauthorized action.');
        }

        try {
           
            $inputs = $request->all();
            $zipcode = new Zipcode();
            $state_id=($request->state_id)?$request->state_id:0;
            $city_id=($request->city_id)?$request->city_id:0;
            $new_zipcode=($request->zipcode)?$request->zipcode:"";
            $rules = $zipcode->rules('update',$state_id,$city_id,$new_zipcode);
            $messages = $zipcode->rulesMessages('update');
            $validator = Validator::make($inputs,$rules,$messages); 
            if($validator->fails()){
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $created_by = request()->session()->get('user.id');
            $zipcodeData = Zipcode::findOrFail($id);
            $updateData = $zipcode->prepareUpdateData($inputs,$zipcodeData);
            $zipcodeData->update($updateData);

            $output = ['success' => true,
                'msg' => __('Zipcode Updated Successfully'),
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
        if (! auth()->user()->can('zipcode.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $created_by = request()->session()->get('user.id');
                $zipcode = Zipcode::findOrFail($id);
                if ($zipcode) {
                    $zipcode->deleted_by = request()->session()->get('user.id');
                    $zipcode->save(); 
                    $zipcode->delete();
                    $output = ['success' => true,
                        'msg' => __('Zipcode Deleted Successfully'),
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

    public function getCities($stateId)
    {
        $cities = City::where('state_id', $stateId)->get();
        return response()->json($cities);
    }
}
