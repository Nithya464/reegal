<?php

namespace App\Http\Controllers;

use App\Business;
use App\BusinessLocation;
use App\Contact;
use App\CustomerGroup;
use App\ProductStock;
use App\TaxRate;
use App\Transaction;
use App\User;
use App\UserContactAccess;
use App\Utils\{BusinessUtil, ProductUtil, TransactionUtil};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderByVendorImController extends Controller{
    
    /**
     * All Utils instance.
     */
    protected $productUtil;

    protected $transactionUtil;

    protected $purchaseOrderStatuses;

    protected $businessUtil;

    /**
     * Constructor
     *
     * @param  ProductUtils  $product
     * @return void
     */
    public function __construct(ProductUtil $productUtil, TransactionUtil $transactionUtil, BusinessUtil $businessUtil)
    {
        $this->productUtil = $productUtil;
        $this->transactionUtil = $transactionUtil;
        $this->businessUtil = $businessUtil;

        $this->purchaseOrderStatuses = [
            'pending' => [
                'label' => __('Pending'),
                'class' => 'bg-info',
            ],
            'revert' => [
                'label' => __('Revert'),
                'class' => 'bg-danger',
            ],
            'completed' => [
                'label' => __('restaurant.completed'),
                'class' => 'bg-green',
            ]
        ];
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! auth()->user()->can('purchase_order_IM.draft_view_all')) {
            abort(403, 'Unauthorized action.');
        }
        
        $business_id = request()->session()->get('user.business_id');
        if (request()->ajax()) {
            $userContactAccess = UserContactAccess::where('user_id',request()->session()->get('user.id'))->pluck('contact_id')->toArray();
            
            $purchase_orders = Transaction::leftJoin('contacts', 'transactions.contact_id', '=', 'contacts.id')
                    // ->join(
                    //     'business_locations AS BS',
                    //     'transactions.location_id',
                    //     '=',
                    //     'BS.id'
                    // )
                    ->leftJoin('purchase_lines as pl', 'transactions.id', '=', 'pl.transaction_id')
                    ->leftJoin('users as u', 'transactions.created_by', '=', 'u.id')
                    ->where('transactions.business_id', $business_id)
                    ->where('transactions.type', 'purchase_order_IR')
                    ->select(
                        'transactions.id',
                        'transactions.transaction_date',
                        'transactions.ref_no',
                        'transactions.status',
                        'transactions.additional_notes',
                        'contacts.name',
                        'contacts.supplier_business_name',
                        'transactions.final_total',
                        //'BS.name as location_name',
                        DB::raw("CONCAT(COALESCE(u.surname, ''),' ',COALESCE(u.first_name, ''),' ',COALESCE(u.last_name,'')) as added_by"),
                        DB::raw('SUM(pl.quantity - pl.po_quantity_purchased) as po_qty_remaining,COUNT(pl.id) as no_of_items')
                    )
                    ->groupBy('transactions.id')->orderBy('transactions.id','DESC');

            $permitted_locations = auth()->user()->permitted_locations();

            if(!empty($userContactAccess)){
                $purchase_orders->whereIn('transactions.contact_id',$userContactAccess);
            }

            if (! empty(request()->supplier_id)) {
                $purchase_orders->where('contacts.id', request()->supplier_id);
            }

            if (! empty(request()->status)) {
                $purchase_orders->where('transactions.status', request()->status);
            }

            if (! empty(request()->start_date) && ! empty(request()->end_date)) {
                $start = request()->start_date;
                $end = request()->end_date;
                $purchase_orders->whereDate('transactions.transaction_date', '>=', $start)
                            ->whereDate('transactions.transaction_date', '<=', $end);
            }   

            $purchase_orders->where('transactions.status','<>', 'draft');
            // if (! auth()->user()->can('purchase_order.view_all') && auth()->user()->can('purchase_order.view_own')) {
            //     $purchase_orders->where('transactions.created_by', request()->session()->get('user.id'));
            // }

            return DataTables::of($purchase_orders)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                                data-toggle="dropdown" aria-expanded="false">'.
                                __('messages.actions').
                                '<span class="caret"></span><span class="sr-only">Toggle Dropdown
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-left" role="menu">';
                    if (auth()->user()->can('purchase_order_IM.draft_view_all') && in_array($row->status,['revert','completed'])) {
                        $html .= '<li><a href="#" data-href="'.action([\App\Http\Controllers\ReceiveStockController::class, 'show'], [$row->id]).'" class="btn-modal" data-container=".view_modal"><i class="fas fa-eye" aria-hidden="true"></i>'.__('messages.view').'</a></li>';
                    }
                    if (auth()->user()->can('purchase_order_IM.draft_view_all') && $row->status == 'pending') {
                        $html .= '<li><a href="'.action([\App\Http\Controllers\PurchaseOrderByVendorImController::class, 'edit'], [$row->id]).'"><i class="fas fa-edit"></i>'.__('messages.edit').'</a></li>';
                    }
                    
                    $html .= '</ul></div>';

                    return $html;
                })
                ->removeColumn('id')
                ->editColumn(
                    'final_total',
                    '<span class="final_total" data-orig-value="{{$final_total}}">@format_currency($final_total)</span>'
                )
                ->editColumn('transaction_date', '{{@format_datetime($transaction_date)}}')
                ->editColumn('po_qty_remaining', '{{@format_quantity($po_qty_remaining)}}')
                ->editColumn('no_of_items', '{{@format_quantity($no_of_items)}}')
                ->editColumn('name', '@if(!empty($supplier_business_name)) {{$supplier_business_name}}, <br> @endif {{$name}}')
                ->editColumn('status', function ($row) {
                    $status = '';
                    $order_statuses = $this->purchaseOrderStatuses;
                    if (array_key_exists($row->status, $order_statuses)) {
                        $status = '<span class="label '.$order_statuses[$row->status]['class']
                            .'" >'.$order_statuses[$row->status]['label'].'</span>';
                    }

                    return $status;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return  action([\App\Http\Controllers\PurchaseOrderByVendorImController::class, 'show'], [$row->id]);
                    }, ])
                ->rawColumns(['final_total', 'action', 'ref_no', 'name', 'status'])
                ->make(true);
        }

        $business_locations = BusinessLocation::forDropdown($business_id);
        $suppliers = Contact::suppliersDropdown($business_id, false);
        $purchaseOrderStatuses = [];
        foreach ($this->purchaseOrderStatuses as $key => $value) {
            $purchaseOrderStatuses[$key] = $value['label'];
        }

        return view('purchase_order.vendor.draft-index')->with(compact('business_locations', 'suppliers', 'purchaseOrderStatuses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! auth()->user()->can('purchase_order_IM.update')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $business = Business::find($business_id);

        $currency_details = $this->transactionUtil->purchaseCurrencyDetails($business_id);

        $taxes = TaxRate::where('business_id', $business_id)
                            ->ExcludeForTaxGroup()
                            ->get();
        $query = Transaction::where('business_id', $business_id)
                    ->where('id', $id)
                    ->with(
                        'contact',
                        'purchase_lines',
                        'purchase_lines.product',
                        'purchase_lines.product.unit',
                        //'purchase_lines.product.unit.sub_units',
                        'purchase_lines.variations',
                        'purchase_lines.variations.product_variation',
                        'location',
                        'purchase_lines.sub_unit',
                        'purchase_lines.purchase_requisition_line'
                    );

        if (! auth()->user()->can('purchase_order.view_all') && auth()->user()->can('purchase_order.view_own')) {
            $query->where('transactions.created_by', request()->session()->get('user.id'));
        }

        $purchase = $query->first();

        foreach ($purchase->purchase_lines as $key => $value) {
            if (! empty($value->sub_unit_id)) {
                $formated_purchase_line = $this->productUtil->changePurchaseLineUnit($value, $business_id);
                $purchase->purchase_lines[$key] = $formated_purchase_line;
            }
        }

        $business_locations = BusinessLocation::forDropdown($business_id);

        $types = [];
        if (auth()->user()->can('supplier.create')) {
            $types['supplier'] = __('report.supplier');
        }
        if (auth()->user()->can('customer.create')) {
            $types['customer'] = __('report.customer');
        }
        if (auth()->user()->can('supplier.create') && auth()->user()->can('customer.create')) {
            $types['both'] = __('lang_v1.both_supplier_customer');
        }
        $customer_groups = CustomerGroup::forDropdown($business_id);

        $business_details = $this->businessUtil->getDetails($business_id);
        $shortcuts = json_decode($business_details->keyboard_shortcuts, true);

        $shipping_statuses = $this->transactionUtil->shipping_statuses();

        //Added check because $users is of no use if enable_contact_assign if false
        $users = config('constants.enable_contact_assign') ? User::forDropdown($business_id, false, false, false, true) : [];

        $delivery_date = ! empty($purchase->delivery_date) ? $this->productUtil->format_date($purchase->delivery_date, true) : null;

        $common_settings = ! empty(session('business.common_settings')) ? session('business.common_settings') : [];

        $purchase_requisitions = null;
        if (! empty($common_settings['enable_purchase_requisition'])) {
            $purchase_requisitions = Transaction::where('business_id', $business_id)
                                        ->where('type', 'purchase_requisition')
                                        ->where('location_id', $purchase->location_id)
                                        ->where(function ($q) use ($purchase) {
                                            $q->where('status', '!=', 'completed');

                                            if (! empty($purchase->purchase_requisition_ids)) {
                                                $q->orWhereIn('id', $purchase->purchase_requisition_ids);
                                            }
                                        })
                                        ->pluck('ref_no', 'id');
        }

        return view('purchase_order.vendor.draft-edit')
            ->with(compact(
                'taxes',
                'purchase',
                'business_locations',
                'business',
                'currency_details',
                'customer_groups',
                'types',
                'shortcuts',
                'shipping_statuses', 'users',
                'delivery_date',
                'common_settings',
                'purchase_requisitions'
            ));
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
        if (! auth()->user()->can('purchase_order_IM.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            
            $transaction = Transaction::findOrFail($id);
            
            $business_id = request()->session()->get('user.business_id');

            $update_data = $request->only(['ref_no', 'contact_id',
                'transaction_date','additional_notes', 'total_before_tax','status']);
            
            $update_data['transaction_date'] = $this->productUtil->uf_date($update_data['transaction_date'], true);
            $update_data['delivery_date'] = $update_data['transaction_date'];

            
            $update_data['total_before_tax'] = $this->productUtil->num_uf($update_data['total_before_tax']);
            
            $update_data['final_total'] = $this->productUtil->num_uf($update_data['total_before_tax']);
            $transaction_before = $transaction->replicate();

            DB::beginTransaction();

            //update transaction
            $transaction->update($update_data);
            
            $purchases = $request->input('purchases');

            $delete_purchase_lines = $this->productUtil->createOrUpdatePurchaseLines($transaction, $purchases, null, true);

            $this->transactionUtil->activityLog($transaction, 'edited', $transaction_before);
            
            if($request->status == 'completed'){
                if($request->purchases && is_array($request->purchases)){
                    $locationId = ((auth()->user()->permitted_locations()[0])) ?? 0;
                    foreach ($request->purchases as $value) {
                        $productStock = ProductStock::where('product_id',$value['product_id'])->where('location_id',$locationId)->latest()->first();
    
                        $productCurrentStock = (($productStock->current_stock)) ?? 0;
    
                        $current_stock = $value['sub_total_quantity'] + $productCurrentStock;
                        $remarkName = request()->session()->get('user.first_name').' '.request()->session()->get('user.last_name');
                        $data=[
                            'product_id' => $value['product_id'],
                            'location_id' => $locationId,
                            'remark' => "Stock received by {$remarkName}",
                            'current_stock' => $current_stock,
                            'reference_id' => $request->ref_no,
                            'updated_by' => request()->session()->get('user.id'),
                            'created_by' => request()->session()->get('user.id')
                        ];
    
                        $current_stock = $productCurrentStock;
                        $data['before_stock'] = $current_stock;
                        $data['affected_stock'] = $value['sub_total_quantity'];
                        
                        ProductStock::create($data);
                    }
                }
            }

            DB::commit();

            $output = ['success' => 1,
                'msg' => __('purchase.purchase_update_success'),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => 0,
                'msg' => $e->getMessage(),
            ];

            return back()->with('status', $output);
        }

        return redirect()->action([\App\Http\Controllers\PurchaseOrderByVendorImController::class, 'index'])->with('status', $output);
    }
}
