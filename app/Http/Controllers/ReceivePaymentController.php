<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;
use Illuminate\Http\Response;

use App\Account;
use App\Business;
use App\BusinessLocation;
use App\Contact;
use App\CustomerGroup;
use App\InvoiceScheme;
use App\Media;
use App\Product;
use App\ProductStock;
use App\ProductUnitPrice;
use App\ReceivePayment;
use App\SellingPriceGroup;
use App\TaxRate;
use App\Transaction;
use App\TransactionSellLine;
use App\TypesOfService;
use App\User;
use App\Utils\BusinessUtil;
use App\Utils\ContactUtil;
use App\Utils\ModuleUtil;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use App\Variation;
use App\Warranty;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class ReceivePaymentController extends Controller
{
    protected $commonUtil;
    public function __construct(ContactUtil $contactUtil, BusinessUtil $businessUtil, TransactionUtil $transactionUtil, ModuleUtil $moduleUtil, ProductUtil $productUtil)
    {
        $this->contactUtil = $contactUtil;
        $this->businessUtil = $businessUtil;
        $this->transactionUtil = $transactionUtil;
        $this->moduleUtil = $moduleUtil;
        $this->productUtil = $productUtil;

        $this->dummyPaymentLine = ['method' => '', 'amount' => 0, 'note' => '', 'card_transaction_number' => '', 'card_number' => '', 'card_type' => '', 'card_holder_name' => '', 'card_month' => '', 'card_year' => '', 'card_security' => '', 'cheque_number' => '', 'bank_account_number' => '',
            'is_return' => 0, 'transaction_no' => '', ];

    }    

    public function index(Request $request)
    {
        $is_admin = $this->businessUtil->is_admin(auth()->user());

        if (! $is_admin && ! auth()->user()->hasAnyPermission(['receive_payment.view'])) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $is_woocommerce = $this->moduleUtil->isModuleInstalled('Woocommerce');
        $is_crm = $this->moduleUtil->isModuleInstalled('Crm');

        if (request()->ajax()) {  
            $receivePay = [];          
            $receivePay = ReceivePayment::get();
            
            $datatable = Datatables::of($receivePay)
            // ->addColumn('action', function ($row) {
            //     return  '<input type="checkbox" class="row-select" value="'.$row->id.'">';
            // })
                ->addColumn(
                    'action',
                    function ($row) {
                        $html = '<div class="btn-group">
                                    <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                                        data-toggle="dropdown" aria-expanded="false">'.
                                        __('messages.actions').
                                        '<span class="caret"></span><span class="sr-only">Toggle Dropdown
                                        </span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-left" role="menu">';

                        // if (auth()->user()->can('direct_sell_pos.view')) {
                            $html .= '<li><a href="'.url('receive-payment/delete').'/'.$row->id.'"><i class="fa fa-trash" aria-hidden="true"></i> '.__('messages.delete').'</a></li>';
                        // }
                        // if (auth()->user()->can('direct_sell_pos.update')) {
                            $html .= '<li><a href="'.url('receive-payment/edit').'/'.$row->id.'"><i class="fas fa-edit"></i> '.__('messages.edit').'</a></li>';
                        //}


                        $html .= '</ul></div>';

                        return $html;
                    }
                )
                ->removeColumn('id')
              
                ->editColumn('payment_mode', function ($row) {
                    return ($row->payment_mode) ?? null;
                }) 
                ->editColumn('payment_id', function ($row) {
                    return ($row->payment_id) ?? null;
                })  
                ->editColumn('receive_date', function ($row) {
                    return ($row->receive_date) ?? null;
                })            
                ->editColumn('conatct_name', function ($row) {
                    return ($row->customer_name) ?? null;
                })
                ->editColumn('receive_amount', function ($row) {
                    return ($row->receive_amount) ?? null;
                })
                ->editColumn('status', function ($row) {
                    return ($row->payment_status) ?? null;
                })
                
                ->editColumn('no_of_order', function ($row) {
                    return (1) ?? null;
                })
                ->editColumn('ref_id', function ($row) {
                    return ($row->reference_id) ?? null;
                })
                ->editColumn('remark', function ($row) {
                    return ($row->remark) ?? null;
                });
              

            $rawColumns = ['payment_id','receive_date', 'action','conatct_name', 'receive_amount','status','','remark','no_of_order'];

            return $datatable->rawColumns($rawColumns)
                      ->make(true);
        }

        $business_locations = BusinessLocation::forDropdown($business_id, false);
        $customers = Contact::customersDropdown($business_id);

        $sales_representative = User::forDropdown($business_id, false, false, true);

        //Commission agent filter
        $is_cmsn_agent_enabled = request()->session()->get('business.sales_cmsn_agnt');
        $commission_agents = [];
        if (! empty($is_cmsn_agent_enabled)) {
            $commission_agents = User::forDropdown($business_id, false, true, true);
        }

        //Service staff filter
        $service_staffs = null;
        if ($this->productUtil->isModuleEnabled('service_staff')) {
            $service_staffs = $this->productUtil->serviceStaffDropdown($business_id);
        }

        $shipping_statuses = $this->transactionUtil->shipping_statuses();

        $sources = $this->transactionUtil->getSources($business_id);
        if ($is_woocommerce) {
            $sources['woocommerce'] = 'Woocommerce';
        }
        $orders = Transaction::where('type','pos_sell')->where('business_id',$business_id)->get();

        return view('pos-payment.index')
        ->with(compact('business_locations', 'customers', 'is_woocommerce',  'shipping_statuses', 'sources','orders'));
    }

    public function getItem(Request $request){
        $business_id = request()->session()->get('user.business_id');
        $orders = Transaction::where('contact_id',$request->id)->where('type','pos_sell')->get();
        if($orders) {
            return Response()->json(['status'=>'success','data' => $orders->toArray()]);
        }
        return Response()->json(['status'=>'error','data' => []]);
    }

    public function create(Request $request){

        $business_id = $request->session()->get('user.business_id');
        $name = Contact::where('id',$request->customer_id)->first();
        $credit = '';
        if($request->due_amount < $request->receive_amount){
            $credit = $request->receive_amount - $request->due_amount;
        }
        $ids = [];
        if(isset($request->selected_ids)) {
            foreach($request->selected_ids as $transactionId) {
                $ids = $transactionId;
            }
        }
        $data=[
            'customer_id'=>$request->customer_id,
            'customer_name'=>$name->name,
            'payment_id'=>rand(1000,9999),
            'due_amount'=>$request->due_amount,
            'receive_date'=>$request->receive_date,
            'receive_amount'=>$request->receive_amount,
            'payment_mode'=>$request->payment_mode,
            'payment_status'=>'new',
            'reference_id'=>$request->reference_id,
            'remark'=>$request->remark,
            'credit_amount'=>$credit,
            'transaction_id'=>$ids,
            'created_by'=>$business_id,
        ];

        $create = ReceivePayment::create($data);
        if($create){

            $output = ['success' => 1,
                'msg' => __('Record Created Successfully'),
            ];
            return redirect()->action([\App\Http\Controllers\ReceivePaymentController::class, 'index'])->with('status',$output);
        }
        $output = ['success' => 0,
                'msg' => __('messages.something_went_wrong'),
            ];
        return redirect()->back()->with('status', $output);
    }

    public function delete($id){
        if($id) {
            $deleteData = ReceivePayment::where('id',$id)->first();
            if($deleteData){
                $deleteData->delete();
                $output = ['success' => 1,
                    'msg' => __('Record Deleted Successfully'),
                ];
                return redirect()->action([\App\Http\Controllers\ReceivePaymentController::class, 'index'])->with('status', $output);              
            }
            $output = ['success' => 0,
                'msg' => __('messages.something_went_wrong'),
            ];

        return redirect()->back()->with('status', $output);

        }

    }
    public function edit($id){
        $is_admin = $this->businessUtil->is_admin(auth()->user());

        if (! $is_admin && ! auth()->user()->hasAnyPermission(['receive_payment.view'])) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $is_woocommerce = $this->moduleUtil->isModuleInstalled('Woocommerce');
        $is_crm = $this->moduleUtil->isModuleInstalled('Crm');
        $is_tables_enabled = $this->transactionUtil->isModuleEnabled('tables');
        $is_service_staff_enabled = $this->transactionUtil->isModuleEnabled('service_staff');
        $is_types_service_enabled = $this->moduleUtil->isModuleEnabled('types_of_service');
        $customerId = ReceivePayment::where('id',$id)->first();
        if (request()->ajax()) {  
            $receivePay = [];          
            $receivePay = ReceivePayment::where('customer_id',$customerId->customer_id)->get();
            
            $datatable = Datatables::of($receivePay)
            // ->addColumn('action', function ($row) {
            //     return  '<input type="checkbox" class="row-select" value="'.$row->id.'">';
            // })
                ->addColumn(
                    'action',
                    function ($row) {
                        $html = '<div class="btn-group">
                                    <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                                        data-toggle="dropdown" aria-expanded="false">'.
                                        __('messages.actions').
                                        '<span class="caret"></span><span class="sr-only">Toggle Dropdown
                                        </span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-left" role="menu">';

                        // if (auth()->user()->can('direct_sell_pos.view')) {
                            $html .= '<li><a href="'.url('receive-payment/delete').'/'.$row->id.'"><i class="fa fa-trash" aria-hidden="true"></i> '.__('messages.delete').'</a></li>';
                        // }
                        // if (auth()->user()->can('direct_sell_pos.update')) {
                            $html .= '<li><a href="'.url('receive-payment/edit').'/'.$row->id.'"><i class="fas fa-edit"></i> '.__('messages.edit').'</a></li>';
                        //}


                        $html .= '</ul></div>';

                        return $html;
                    }
                )
                ->removeColumn('id')
            
                ->editColumn('payment_mode', function ($row) {
                    return ($row->payment_mode) ?? null;
                })
                ->editColumn('payment_id', function ($row) {
                    return ($row->payment_id) ?? null;
                })  
                ->editColumn('receive_date', function ($row) {
                    return ($row->receive_date) ?? null;
                })            
                ->editColumn('conatct_name', function ($row) {
                    return ($row->customer_name) ?? null;
                })
                ->editColumn('receive_amount', function ($row) {
                    return ($row->receive_amount) ?? null;
                })
                ->editColumn('status', function ($row) {
                    return ($row->payment_status) ?? null;
                })
                
                ->editColumn('no_of_order', function ($row) {
                    return (1) ?? null;
                })
                ->editColumn('ref_id', function ($row) {
                    return ($row->reference_id) ?? null;
                })
                ->editColumn('remark', function ($row) {
                    return ($row->remark) ?? null;
                });
              

            $rawColumns = ['payment_id','receive_date', 'action','conatct_name', 'receive_amount','status','','remark','no_of_order'];

            return $datatable->rawColumns($rawColumns)
                      ->make(true);
        }

        $business_locations = BusinessLocation::forDropdown($business_id, false);
        $customers = Contact::where('type','customer')->get();
        $sales_representative = User::forDropdown($business_id, false, false, true);

        //Commission agent filter
        $is_cmsn_agent_enabled = request()->session()->get('business.sales_cmsn_agnt');
        $commission_agents = [];
        if (! empty($is_cmsn_agent_enabled)) {
            $commission_agents = User::forDropdown($business_id, false, true, true);
        }

        //Service staff filter
        $service_staffs = null;
        if ($this->productUtil->isModuleEnabled('service_staff')) {
            $service_staffs = $this->productUtil->serviceStaffDropdown($business_id);
        }

        $shipping_statuses = $this->transactionUtil->shipping_statuses();

        $sources = $this->transactionUtil->getSources($business_id);
        if ($is_woocommerce) {
            $sources['woocommerce'] = 'Woocommerce';
        }
        $payment_list = ReceivePayment::where('id',$id)->first();
        $orders = Transaction::where('contact_id',$payment_list->customer_id)->where('type','pos_sell')->get();

        return view('pos-payment.edit')
        ->with(compact('business_locations', 'customers','payment_list', 'is_woocommerce', 'sales_representative', 'is_cmsn_agent_enabled', 'commission_agents', 'service_staffs', 'is_tables_enabled', 'is_service_staff_enabled', 'is_types_service_enabled', 'shipping_statuses', 'sources','orders'));
    }

    public function save(Request $request){
        $business_id = $request->session()->get('user.business_id');
        $name = Contact::where('id',$request->customer_id)->first();
        $credit = '';
        if($request->due_amount < $request->receive_amount){
            $credit = $request->receive_amount - $request->due_amount;
        }
        $ids = [];
        if(isset($request->selected_ids)) {
            $ids = [];
            foreach($request->selected_ids as $transactionId) {
                $ids = $transactionId;
            }
        }
        $data=[
          
            'due_amount'=>$request->due_amount,
            'receive_date'=>$request->receive_date,
            'receive_amount'=>$request->receive_amount,
            'payment_mode'=>$request->payment_mode,
            'payment_status'=>'new',
            'reference_id'=>$request->reference_id,
            'remark'=>$request->remark,
            'credit_amount'=>$credit,
            'transaction_id'=>($ids) ? $ids : '',
            'created_by'=>$business_id,
        ];
        
        $updateData = ReceivePayment::where('id',$request->id)->update($data);
        if($updateData){

            $output = ['success' => 1,
                'msg' => __('Record Updated Successfully.'),
            ];
            return redirect()->route('receive_payment_edit', ['id' => $request->id])->with('status', $output);
        }
        $output = ['success' => 0,
                'msg' => __('messages.something_went_wrong'),
            ];

        return redirect()->back()->with('status', $output);

    }
}
