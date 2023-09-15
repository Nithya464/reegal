<?php

namespace App\Http\Controllers;

use App\State;
use App\Tax;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!auth()->user()->can('tax.view')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $taxes = Tax::with('state')->orderBy('id', 'desc')->get();
        
            return DataTables::of($taxes)
                ->addColumn('state_name', function ($taxes) {
                    return $taxes->state->state_name;
                })
                ->editColumn('status', function ($row) {
                    $status=($row->status == Tax::ACTIVE_STATUS) ? 'Active' : 'De-Active';
                    $badge=($row->status == Tax::ACTIVE_STATUS) ? 'success' : 'danger';
                    return '<span class="badge badge-success">'.$status.'</span>';
                })
                ->addColumn('action', function ($row) {
                    $actionHtml = '';
                    $actionHtml = '';
                    if (auth()->user()->can("state.update")) {
                        $actionHtml .= '<button  data-href="' . action('App\Http\Controllers\TaxController@edit', [$row->id]) . '" class="btn btn-xs btn-primary edit_tax_button" title="Edit"><i class="glyphicon glyphicon-edit"></i></button> &nbsp;';
                    }
                    if (auth()->user()->can("state.delete")) {
                        $actionHtml .= '<button data-href="' . action('App\Http\Controllers\TaxController@destroy', [$row->id]) . '" class="btn btn-xs btn-danger delete_tax_button"><i class="glyphicon glyphicon-trash"></i></button>';
                    }
                    return $actionHtml;
                })


                // ->removeColumn('id')
                ->rawColumns(['state_name','action', 'status'])
                ->make(true);
        }

        return view('tax.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        if (!auth()->user()->can('tax.create')) {
            abort(403, 'Unauthorized action.');
        }

        $states = State::where(['status' => '1'])->pluck('state_name', 'id')
        ->toArray();
        return view('tax.create',compact('states'));
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
            if (!auth()->user()->can('tax.create')) {
                abort(403, 'Unauthorized action.');
            }

            $inputs = $request->all();
            $tax = new Tax();
            $rules = $tax->rules('create');
            
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $createData = $tax->prepareCreateData($inputs);

            $tax = tax::create($createData);
            $output = [
                'success' => true,
                'data' => $tax,
                'msg' => __('tax.added_success'),
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
     * @param  \App\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function show(Tax $tax)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('tax.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $created_by = request()->session()->get('user.id');
            $tax = tax::findOrFail($id);
            $states = State::where(['status' => '1'])
                        ->pluck('state_name', 'id')
                        ->toArray();
            return view('tax.edit')
                ->with(compact('tax','states'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('tax.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $inputs = $request->all();
                $tax = new Tax();
                $rules = $tax->rules('update');
                $validator = Validator::make($inputs, $rules);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }
          
                // $created_by = request()->session()->get('user.id');
                $taxData = Tax::findOrFail($id);
                $updateData = $tax->prepareUpdateData($inputs, $taxData);
                $taxData->update($updateData);

                $output = [
                    'success' => true,
                    'msg' => __('tax.updated_success'),
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
     * @param  \App\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('tax.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $created_by = request()->session()->get('user.id');
                $tax = Tax::findOrFail($id);
                if ($tax) {
                    $tax->deleted_by = request()->session()->get('user.id');
                    $tax->save();
                    $tax->delete();
                    $output = [
                        'success' => true,
                        'msg' => __('tax.deleted_success'),
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
