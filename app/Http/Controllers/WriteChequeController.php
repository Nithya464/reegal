<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\User;
use App\WriteCheque;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class WriteChequeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        if (!auth()->user()->can('write_cheque.view')){
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $search = $request->search;
            $type = $request->type;
            $writecheque = WriteCheque::with('employee')->orderBy('id', 'desc');
            if(!empty($type)){
                $writecheque = $writecheque->where('type',$type);
            }
            $writecheque->get();   
            return DataTables::of($writecheque)
                // ->editColumn('id', function ($row) {
                //   return str_pad($row->id, 4, '0', STR_PAD_LEFT);
                // })
                ->addColumn('name', function ($row) {
                    $data = '';
                    if(isset($row->employee_id)) {
                        $data =  $row->employee->username;
                    } else {
                        $data =  $row->vendor->vendor_name;
                    }
                    return $data;
                  })
                  ->editColumn('type', function ($row) {
                    return ((config('global.write_check_type_options.' . $row->type))) ?? '-';
                })
                  ->editColumn('created_by', function ($row) {
                   $data = User::find($row->created_by);
                   $createdby = $data->username;
                    return $createdby;
                  })
                  ->addColumn('action', function ($row) {
                        $actionHtml = '';
                        if (auth()->user()->can("write_cheque.update")) {
                           $actionHtml .= '<button  data-href="'.action('App\Http\Controllers\WriteChequeController@edit', [$row->id]).'" class="btn btn-xs btn-primary edit_write_check_button" title="Edit"><i class="glyphicon glyphicon-edit"></i></button> &nbsp;';
                        }
                        if (auth()->user()->can("write_cheque.delete")) {
                            $actionHtml .= '<button data-href="'.action('App\Http\Controllers\WriteChequeController@destroy', [$row->id]).'" class="btn btn-xs btn-danger delete_write_check_button"><i class="glyphicon glyphicon-trash"></i></button>';
                        }
                        return $actionHtml;
                    })

                ->rawColumns(['action','name','status'])
                ->make(true);
        }
        return view('write_check.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! auth()->user()->can('write_cheque.create')) {
            abort(403, 'Unauthorized action.');
        }
        $users = User::where('status', 'active')->pluck('username', 'id')
                ->toArray();
        $vendors = Vendor::where('status', '1')->pluck('vendor_name', 'id')
                ->toArray();     
        return view('write_check.create', compact('users','vendors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo "<pre>"; print_r($request->all());exit;
        try {
            if (!auth()->user()->can('write_cheque.create')) {
                abort(403, 'Unauthorized action.');
            }
            $created_by = request()->session()->get('user.id');
            $inputs = $request->all();
            $writecheck = new WriteCheque();
            $rules = $writecheck->rules('create');
            
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $createData = $writecheck->prepareCreateData($inputs);

            $writecheck = WriteCheque::create($createData);
            $output = [
                'success' => true,
                'data' => $writecheck,
                'msg' => __('writecheck.added_success'),
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
     * @param  \App\WriteCheque  $WriteCheque
     * @return \Illuminate\Http\Response
     */
    public function show(WriteCheque $WriteCheque)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WriteCheque  $WriteCheque
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        if (!auth()->user()->can('write_cheque.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $created_by = request()->session()->get('user.id');
            $writecheck = WriteCheque::findOrFail($id);
            $users = User::where('status', 'active')->pluck('username', 'id')
            ->toArray();
            $vendors = Vendor::where('status', '1')->pluck('vendor_name', 'id')
            ->toArray();   
            return view('write_check.edit')
                ->with(compact('writecheck','users','vendors'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WriteCheque  $WriteCheque
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        if (!auth()->user()->can('write_cheque.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $inputs = $request->all();
                $writecheck = new WriteCheque();
                $rules = $writecheck->rules('update');
                $validator = Validator::make($inputs, $rules);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }
                $writecheckData = WriteCheque::findOrFail($id);
                $updatecheckwriteData = $writecheck->prepareUpdateData($inputs, $writecheckData);
                $writecheckData->update($updatecheckwriteData);

                $output = [
                    'success' => true,
                    'msg' => __('writecheque.updated_success'),
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
     * @param  \App\WriteCheque  $writeCheck
     * @return \Illuminate\Http\Response
     */
    public function destroy(WriteCheque $WriteCheque,$id)
    {
        if (!auth()->user()->can('write_cheque.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $created_by = request()->session()->get('user.id');
                $writecheck = WriteCheque::findOrFail($id);      
                if ($writecheck) {
                    $writecheck->deleted_by = request()->session()->get('user.id');
                    // $writecheck->save();
                    $writecheck->delete();
                    $output = [
                        'success' => true,
                        'msg' => __('writecheck.deleted_success'),
                    ];
                }
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
