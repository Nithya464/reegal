<?php

namespace App\Http\Controllers;

use App\Vendor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use App\Utils\Util;

class VendorController extends Controller
{
    protected $moduleUtil;
    public function __construct(Util $moduleUtil)
    {
        $this->moduleUtil = $moduleUtil;
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('vendor.view')) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->ajax()) {
            $vendors = Vendor::orderBy('id', 'desc')->get();
            return Datatables::of($vendors)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    $status = ($row->status == '1') ? 'Active' : 'Inactive';
                    return '<span class="badge badge-success">' . $status . '</span>';
                })
                ->editColumn('sub_type', function ($row) {
                  return ((config('global.subtype_options.' . $row->sub_type))) ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $actionHtml = '';
                    if (auth()->user()->can('vendor.update')) {
                        $actionHtml .= '<a href="' . action('App\Http\Controllers\VendorController@edit', [$row->id]) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;';
                    }
                    if (auth()->user()->can('vendor.delete')) {
                        $actionHtml .= '<button data-href="' . action('App\Http\Controllers\VendorController@destroy', [$row->id]) . '" class="btn btn-xs btn-danger delete_vendor_button"><i class="glyphicon glyphicon-trash"></i></button>';

                    }
                    return $actionHtml;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('vendor_module.index');

    }

    public function create()
    {
        return view('vendor_module.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('vendor.create')) {
            abort(403, 'Unauthorized action.');
        }

        $inputs = $request->all();
        $vendor = new Vendor();
        $vendor_id = ($request->vendor_id) ? $request->vendor_id : 0;
        $rules = $vendor->rules('create', $vendor_id);
        $messages = $vendor->rulesMessages('create');
        $validator = Validator::make($inputs, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

         //Update reference count
         $ref_count = $this->moduleUtil->setAndGetReferenceCount('vendors');

         if (empty($inputs['vendor_id'])) {
             //Generate reference number
             $inputs['vendor_id'] = 'VEN'.$this->moduleUtil->generateReferenceNumber('vendors', $ref_count);
         }
        

        $createData = $vendor->prepareCreateData($inputs);

        $vendor = Vendor::create($createData);
        if ($vendor) {
            return redirect()->action([\App\Http\Controllers\VendorController::class, 'index'])->with('success', 'Vendor record saved successfully.');
            ;
            // return redirect()->route('car')->with('success', 'Car Created Successfully.');
        }
        return redirect()->back()->with('error', 'Somthing Went Wrong.');

    }
    public function edit($id)
    {
        if (!auth()->user()->can('vendor.update')) {
            abort(403, 'Unauthorized action.');
        }
        $vendor_id = request()->session()->get('user.id');
        $vendor = Vendor::where('created_by', $vendor_id)->where('id', $id)->firstOrFail();
        return view('vendor_module.edit')->with(compact('vendor'))->render();
    }
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('vendor.update')) {
            abort(403, 'Unauthorized action.');
        }


        $inputs = $request->all();
        $city = new Vendor();
        $vendor_id = ($request->vendor_id) ? $request->vendor_id : 0;
        $rules = $city->rules('update', $vendor_id);
        $messages = $city->rulesMessages('update');
        $validator = Validator::make($inputs, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $created_by = request()->session()->get('user.id');
        $vendorData = Vendor::findOrFail($id);
        $updateData = $city->prepareUpdateData($inputs, $vendorData);
        $vendorData->update($updateData);

        if ($vendorData) {
            return redirect()->action([\App\Http\Controllers\VendorController::class, 'index'])->with('success', 'Vendor record Updated successfully.');
            // return redirect()->route('car')->with('success', 'Car Created Successfully.');
        }
        return redirect()->back()->with('error', 'Somthing Went Wrong.');
    }
    public function destroy($id)
    {
        if (!auth()->user()->can('vendor.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $created_by = request()->session()->get('user.id');
                $vendor = Vendor::findOrFail($id);
                if ($vendor) {
                    $vendor->deleted_by = request()->session()->get('user.id');
                    $vendor->save();
                    $vendor->delete();
                    $output = [
                        'success' => true,
                        'msg' => __('Vendo Deleted Successfully'),
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