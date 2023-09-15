<?php

namespace App\Http\Controllers;

use App\State;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class StateController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('state.view')){
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $created_by = request()->session()->get('user.id');
            $states = State::all();
            return Datatables::of($states)
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
                        if (auth()->user()->can("state.update")) {
                           $actionHtml .= '<button  data-href="'.action('App\Http\Controllers\StateController@edit', [$row->id]).'" class="btn btn-xs btn-primary edit_state_button" title="Edit"><i class="glyphicon glyphicon-edit"></i></button> &nbsp;';
                        }
                        if (auth()->user()->can("state.delete")) {
                            $actionHtml .= '<button data-href="'.action('App\Http\Controllers\StateController@destroy', [$row->id]).'" class="btn btn-xs btn-danger delete_state_button"><i class="glyphicon glyphicon-trash"></i></button>';
                        }
                        return $actionHtml;
                    })

                ->rawColumns(['action','status'])
                ->make(true);
        }

        return view('state.index');
    }

    
    public function create()
    {
        if (! auth()->user()->can('state.create')) {
            abort(403, 'Unauthorized action.');
        }   
        return view('state.create');
    }

   
    public function store(Request $request)
    {
        if (! auth()->user()->can('state.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $inputs = $request->all();
            $state = new State();
            $rules = $state->rules('create');
            $messages = $state->rulesMessages('create');
            $validator = Validator::make($inputs,$rules,$messages); 
            if($validator->fails()){
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            $createData = $state->prepareCreateData($inputs);
           
            $state = State::create($createData);
            $output = ['success' => true,
                'data' => $state,
                'msg' => __('State Added Successfully'),
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
        
        if (! auth()->user()->can('state.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $created_by =request()->session()->get('user.id');
            $state = State::find($id);
            
            return view('state.edit')
                ->with(compact('state'));
        }
    }

    
    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('state.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $inputs = $request->all();
                $state = new State();
                $rules = $state->rules('update');
                $messages = $state->rulesMessages('update');
                $validator = Validator::make($inputs,$rules,$messages); 
                if($validator->fails()){
                    return response()->json(['errors' => $validator->errors()], 422);
                }
                $created_by = request()->session()->get('user.id');
                $stateData = State::findOrFail($id);
                $updateData = $state->prepareUpdateData($inputs,$stateData);
                $stateData->update($updateData);

                $output = ['success' => true,
                    'msg' => __('State Updated Successfully'),
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


    public function destroy($id)
    {
        if (! auth()->user()->can('state.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $created_by = request()->session()->get('user.id');
                $state = State::findOrFail($id);
                if ($state) {
                    $state->deleted_by = request()->session()->get('user.id');
                    $state->save(); 
                    $state->delete();
                    $output = ['success' => true,
                        'msg' => __('State Deleted Successfully'),
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
