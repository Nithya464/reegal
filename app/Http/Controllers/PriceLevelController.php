<?php

namespace App\Http\Controllers;

use App\PriceLevel;
use App\Utils\Util;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PriceLevelController extends Controller
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
            $routes = PriceLevel::select(['id','price_level_id','name','customer_type', 'status','created_by']);

            return DataTables::of($routes)
                ->editColumn('created_by', function ($row) {
                    return ($row->createdBy)?$row->createdBy->full_name:"-";
                })
                ->editColumn('customer_type', function ($row) {
                    $globlCustomerType = config('global.customer_type');
                    return $globlCustomerType[$row->customer_type];
                })
                ->editColumn('status', function ($row) {
                    $globlStatus = config('global.status');
                    return "<span class='badge rounded-pill {$globlStatus[$row->status]["class"]}'>{$globlStatus[$row->status]['val']}</span>";
                }) 
                ->addColumn(
                    'action',
                    '@can("price_level.update")
                    <button data-href="{{action(\'App\Http\Controllers\PriceLevelController@edit\', [$id])}}" class="btn btn-xs btn-primary edit_price_level_button" title="Edit"><i class="glyphicon glyphicon-edit"></i></button>
                        &nbsp;
                    @endcan
                    @can("price_level.delete")
                        <button data-href="{{action(\'App\Http\Controllers\PriceLevelController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_price_level_button" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>
                    @endcan'
                )
                ->removeColumn('id')
                ->rawColumns([3,5])
                ->make(false);
        }

        return view('price_level.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! auth()->user()->can('price_level.create')) {
            abort(403, 'Unauthorized action.');
        }

        $quick_add = false;
        if (! empty(request()->input('quick_add'))) {
            $quick_add = true;
        }
        
        return view('price_level.create')
                ->with(compact('quick_add'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        if (! auth()->user()->can('price_level.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['price_level_id', 'name','customer_type','status']);
            $input['created_by'] = $request->session()->get('user.id');

            //Update reference count
            $ref_count = $this->moduleUtil->setAndGetReferenceCount('price_levels');

            if (empty($input['price_level_id'])) {
                //Generate reference number
                $input['price_level_id'] = $this->moduleUtil->generateReferenceNumber('price_levels', $ref_count);
            }

            $priceLevel = PriceLevel::create($input);

            $output = ['success' => true,
                'data' => $priceLevel,
                'msg' => __('Price Level added successfully'),
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
        if (! auth()->user()->can('price_level.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $priceLevel = PriceLevel::find($id);
          
            return view('price_level.edit')
                ->with(compact('priceLevel'));
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
        if (! auth()->user()->can('price_level.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $input = $request->only(['name','customer_type','status']);

                $priceLevel = PriceLevel::findOrFail($id);
                $priceLevel->name = $input['name'];
                $priceLevel->customer_type = $input['customer_type'];
                $priceLevel->status = $input['status'];
                    
                $priceLevel->save();
                
                $output = ['success' => true,
                    'msg' => __('Price Level updated successfully'),
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
        if (! auth()->user()->can('price_level.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $priceLevel = PriceLevel::findOrFail($id);
                $priceLevel->delete();

                $output = ['success' => true,
                    'msg' => __('Price Level deleted successfully'),
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
