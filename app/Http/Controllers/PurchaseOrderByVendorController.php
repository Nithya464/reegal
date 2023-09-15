<?php

namespace App\Http\Controllers;

use App\Business;
use App\BusinessLocation;
use App\Contact;
use App\CustomerGroup;
use App\PurchaseLine;
use App\TaxRate;
use App\Transaction;
use App\User;
use App\Utils\{BusinessUtil,ModuleUtil, ProductUtil, TransactionUtil};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderByVendorController extends Controller{

    /**
     * All Utils instance.
     */
    protected $productUtil;

    protected $transactionUtil;

    protected $businessUtil;

    protected $moduleUtil;

    protected $purchaseOrderStatuses;

    /**
     * Constructor
     *
     * @param  ProductUtils  $product
     * @return void
     */
    public function __construct(ProductUtil $productUtil, TransactionUtil $transactionUtil, BusinessUtil $businessUtil, ModuleUtil $moduleUtil)
    {
        $this->productUtil = $productUtil;
        $this->transactionUtil = $transactionUtil;
        $this->businessUtil = $businessUtil;
        $this->moduleUtil = $moduleUtil;

        $this->purchaseOrderStatuses = [
            'draft' => [
                'label' => __('Draft'),
                'class' => 'bg-manufacturing',
            ],
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
        if (! auth()->user()->can('purchase_order_IR.view_all')) {
            abort(403, 'Unauthorized action.');
        }
        
        $business_id = request()->session()->get('user.business_id');
        if (request()->ajax()) {
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
                        'transactions.pay_term_number',
                        'transactions.pay_term_type',
                        'transactions.shipping_status',
                        DB::raw("CONCAT(COALESCE(u.surname, ''),' ',COALESCE(u.first_name, ''),' ',COALESCE(u.last_name,'')) as added_by"),
                        DB::raw('SUM(pl.quantity - pl.po_quantity_purchased) as po_qty_remaining,COUNT(pl.id) as no_of_items')
                    )
                    ->groupBy('transactions.id')->orderBy('transactions.id','DESC');

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $purchase_orders->whereIn('transactions.location_id', $permitted_locations);
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
                    if (auth()->user()->can('purchase_order_IR.view_all') && in_array($row->status,['pending','completed'])) {
                        $html .= '<li><a href="#" data-href="'.action([\App\Http\Controllers\PurchaseOrderByVendorController::class, 'show'], [$row->id]).'" class="btn-modal" data-container=".view_modal"><i class="fas fa-eye" aria-hidden="true"></i>'.__('messages.view').'</a></li>';

                        $html .= '<li><a href="#" class="print-invoice" data-href="'.action([\App\Http\Controllers\PurchaseController::class, 'printInvoice'], [$row->id]).'"><i class="fas fa-print" aria-hidden="true"></i>'.__('messages.print').'</a></li>';
                    }
                    if (auth()->user()->can('purchase_order_IR.update') && in_array($row->status,['revert','draft'])) {
                        $html .= '<li><a href="'.action([\App\Http\Controllers\PurchaseOrderByVendorController::class, 'edit'], [$row->id]).'"><i class="fas fa-edit"></i>'.__('messages.edit').'</a></li>';
                    }
                    if (auth()->user()->can('purchase_order_IR.delete') && $row->status !== 'completed') {
                        $html .= '<li><a href="'.action([\App\Http\Controllers\PurchaseOrderByVendorController::class, 'destroy'], [$row->id]).'" class="delete-purchase-order"><i class="fas fa-trash"></i>'.__('messages.delete').'</a></li>';
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
                        return  action([\App\Http\Controllers\PurchaseOrderByVendorController::class, 'show'], [$row->id]);
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

        return view('purchase_order.vendor.index')->with(compact('business_locations', 'suppliers', 'purchaseOrderStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        if (! auth()->user()->can('purchase_order_IR.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $taxes = TaxRate::where('business_id', $business_id)
                        ->ExcludeForTaxGroup()
                        ->get();

        $business_locations = BusinessLocation::forDropdown($business_id, false, true, true, false);
        $bl_attributes = $business_locations['attributes'];
        $business_locations = $business_locations['locations'];

        $currency_details = $this->transactionUtil->purchaseCurrencyDetails($business_id);

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
        $locations = auth()->user()->permitted_locations($business_id);
        if($locations == 'all'){
            $fromLocations = $business_locations;
        }else{
            $fromLocations = BusinessLocation::select(DB::raw("IF(location_id IS NULL OR location_id='', name, CONCAT(name, ' (', location_id, ')')) AS name"),
            'id',)->whereIn('id',$locations)->get()->pluck('name', 'id');
        }

        return view('purchase_order.vendor.create')
            ->with(compact('business_locations', 'currency_details', 'customer_groups', 'types', 'shortcuts','bl_attributes','fromLocations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('purchase_order_IR.create')) {
            abort(403, 'Unauthorized action.');
        }
        
        try {
            $business_id = $request->session()->get('user.business_id');
        
            $transaction_data = $request->only(['ref_no', 'contact_id', 'transaction_date','additional_notes','total_before_tax','status']);

            //Adding temporary fix by validating
            $request->validate([
                'contact_id' => 'required',
                'transaction_date' => 'required',
                'total_before_tax' => 'required',
            ]);

            $user_id = $request->session()->get('user.id');
            $transaction_data['total_before_tax'] = $this->productUtil->num_uf($transaction_data['total_before_tax']);

            $transaction_data['final_total'] = $this->productUtil->num_uf($transaction_data['total_before_tax']);

            $transaction_data['business_id'] = $business_id;
            $transaction_data['location_id'] = ((auth()->user()->permitted_locations()[0])) ?? 0;
            $transaction_data['created_by'] = $user_id;
            $transaction_data['type'] = 'purchase_order_IR';
            $transaction_data['shipping_status'] = 'new';
            $transaction_data['transaction_date'] = $this->productUtil->uf_date($transaction_data['transaction_date'], true);
            $transaction_data['delivery_date'] = $transaction_data['transaction_date'];

            DB::beginTransaction();


            $transaction = Transaction::create($transaction_data);
            $purchases = $request->input('purchases');

            $this->productUtil->createOrUpdatePurchaseLines($transaction, $purchases);

            $this->transactionUtil->activityLog($transaction, 'added');

            DB::commit();

            $output = ['success' => 1,
                'msg' => __('lang_v1.added_success'),
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => 0,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return redirect()->action([\App\Http\Controllers\PurchaseOrderByVendorController::class, 'index'])->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! auth()->user()->can('purchase_order_IR.view_all')) {
            abort(403, 'Unauthorized action.');
        }
        $business_id = request()->session()->get('user.business_id');
        $taxes = TaxRate::where('business_id', $business_id)
                            ->pluck('name', 'id');
        $query = Transaction::where('business_id', $business_id)
                                ->where('id', $id)
                                ->with(
                                    'contact',
                                    'purchase_lines',
                                    'purchase_lines.product',
                                    'purchase_lines.product.unit',
                                    'purchase_lines.variations',
                                    'purchase_lines.variations.product_variation',
                                    'purchase_lines.sub_unit',
                                    'location',
                                    'tax'
                                );

        $purchase = $query->firstOrFail();
        
        foreach ($purchase->purchase_lines as $key => $value) {
            if (! empty($value->sub_unit_id)) {
                $formated_purchase_line = $this->productUtil->changePurchaseLineUnit($value, $business_id);
                $purchase->purchase_lines[$key] = $formated_purchase_line;
            }
        }

        $purchase_taxes = [];
        if (! empty($purchase->tax)) {
            if ($purchase->tax->is_tax_group) {
                $purchase_taxes = $this->transactionUtil->sumGroupTaxDetails($this->transactionUtil->groupTaxDetails($purchase->tax, $purchase->tax_amount));
            } else {
                $purchase_taxes[$purchase->tax->name] = $purchase->tax_amount;
            }
        }

        $activities = Activity::forSubject($purchase)
           ->with(['causer', 'subject'])
           ->latest()
           ->get();

        $shipping_statuses = $this->transactionUtil->shipping_statuses();
        $status_color_in_activity = $this->purchaseOrderStatuses;
        $po_statuses = $this->purchaseOrderStatuses;
        return view('purchase_order.show')
                ->with(compact('taxes', 'purchase', 'purchase_taxes', 'activities', 'shipping_statuses', 'status_color_in_activity', 'po_statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! auth()->user()->can('purchase_order_IR.update')) {
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

        return view('purchase_order.vendor.edit')
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
        if (! auth()->user()->can('purchase_order_IR.update')) {
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

        return redirect()->action([\App\Http\Controllers\PurchaseOrderByVendorController::class, 'index'])->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! auth()->user()->can('purchase_order_IR.delete')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            if (request()->ajax()) {
                $business_id = request()->session()->get('user.business_id');

                $transaction = Transaction::where('business_id', $business_id)
                                ->where('type', 'purchase_order_IR')
                                ->with('purchase_lines')
                                ->findOrFail($id);

                //unset purchase_order_line_id if set
                PurchaseLine::whereIn('purchase_order_line_id', $transaction->purchase_lines->pluck('id'))
                        ->update(['purchase_order_line_id' => null]);

                $log_properities = [
                    'id' => $transaction->id,
                    'ref_no' => $transaction->ref_no,
                ];
                $this->transactionUtil->activityLog($transaction, 'po_deleted', null, $log_properities);

                $transaction->delete();

                $output = ['success' => true,
                    'msg' => __('lang_v1.purchase_order_delete_success'),
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => $e->getMessage(),
            ];
        }

        return $output;
    }
}
