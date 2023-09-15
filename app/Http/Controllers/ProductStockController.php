<?php

namespace App\Http\Controllers;

use App\BusinessLocation;
use App\Product;
use App\ProductStock;
use App\ProductUnitPrice;
use App\Utils\ModuleUtil;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class ProductStockController extends Controller
{
   
    protected $productUtil;
    protected $transactionUtil;
    protected $moduleUtil;
    public $status_colors;
    
    public function __construct(ProductUtil $productUtil, TransactionUtil $transactionUtil, ModuleUtil $moduleUtil){
        $this->productUtil = $productUtil;
        $this->transactionUtil = $transactionUtil;
        $this->moduleUtil = $moduleUtil;
        $this->status_colors = [
            'in_transit' => 'bg-yellow',
            'completed' => 'bg-green',
            'pending' => 'bg-red',
        ];
    }


    public function create(){
        if (! auth()->user()->can('stock.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $products = Product::join('variations', 'products.id', '=', 'variations.product_id')
        ->active()
        ->whereNull('variations.deleted_at')
        ->leftjoin('units as U', 'products.unit_id', '=', 'U.id')->pluck('products.name', 'products.id');
        
        $business_locations = BusinessLocation::forDropdown($business_id);

        $statuses = [];
        
        return view('stock.create')
                ->with(compact('business_locations', 'statuses','products'));
    }

    public function store(Request $request){
        $permitted_locations = auth()->user()->permitted_locations();
        $productStock = ProductStock::where('product_id',$request->product_id)->whereIn('location_id',$permitted_locations)->latest()->first();
        $data=[
            'barcode' => $request->barcode,
            'product_id' => $request->product_id,
            'location_id' => (($permitted_locations[0])) ?? 0,
            'remark' => $request->remark,
            'current_stock' => $request->current_stock,
            'updated_by' => request()->session()->get('user.id'),
            'created_by' => request()->session()->get('user.id')
        ];

        $current_stock = (($productStock->current_stock)) ?? 0;
        $data['before_stock'] = $current_stock;
        $data['affected_stock'] = ($request->current_stock - $current_stock);
        
        $createData=ProductStock::create($data);

        if($createData){
            return Redirect::back()->with('success', 'Stock Update Successfully!');
        }
        return Redirect::back()->with('failure', 'Something Went Wrong!!');
    }

  

    public function getUnit(Request $request){
        $datas = [];
        $productId = $request->product_id;
        $permitted_locations = auth()->user()->permitted_locations();
        // Available in Stock
        $currentStock = ProductStock::select('product_id','current_stock')->whereIn('location_id',$permitted_locations)->where('product_id',$productId)->orderBy('id','desc')->first();
        $datas['current_stock'] = (($currentStock->current_stock)) ?? 0;

        // Product Available Units
        $datas['product_units'] = ProductUnitPrice::where('product_id',$productId)->active()->get();
        
        // Product Available Stocks
        $datas['product_stocks'] = ProductStock::where('product_id', $productId)->whereIn('location_id',$permitted_locations)->orderBy('id','DESC')->get();
        
        return response()->json([
            'status' => true,
            'data' => $datas
        ]); 
    }

    public function getUpdateStockLog(Request $request){
        $permitted_locations = auth()->user()->permitted_locations();
        $datas = ProductStock::select('product_id','before_stock','affected_stock','current_stock','remark','reference_id','created_at','updated_by')->where('product_id', $request->product_id)->whereIn('location_id',$permitted_locations)->orderBy('id','DESC');
        
        return DataTables::of($datas)
            ->addColumn('created_at', function ($row) {
                return date('d/m/Y H:i A',strtotime($row->created_at));
            })
            ->addColumn('updated_by', function ($row) {
                return $row->user->first_name.' '.$row->user->last_name;
            })
            ->addColumn('defaul_affected_stock', function ($row) {
                return $row->affected_stock / $row->defaultUnit->qty .' '.$row->defaultUnit->unit;
            })
            ->addColumn('current_stock_in_defaul_unit', function ($row) {
                return $row->current_stock / $row->defaultUnit->qty .' '.$row->defaultUnit->unit;
            })
            ->removeColumn('product_id')
            ->make(true);   
    }
}
