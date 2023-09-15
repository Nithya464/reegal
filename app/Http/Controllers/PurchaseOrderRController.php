<?php

namespace App\Http\Controllers;

use App\BusinessLocation;
use App\Contact;
use App\Transaction;
use App\Utils\{BusinessUtil, TransactionUtil};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderRController extends Controller{

    /**
     * All Utils instance.
     */
    protected $transactionUtil;

    protected $businessUtil;

    protected $shipping_status_colors;

    protected $purchaseOrderStatuses;
    /**
     * Constructor
     *
     * @param  ProductUtils  $product
     * @return void
     */
    public function __construct(TransactionUtil $transactionUtil, BusinessUtil $businessUtil)
    {
        $this->transactionUtil = $transactionUtil;
        $this->businessUtil = $businessUtil;

        $this->purchaseOrderStatuses = [
            'pending' => [
                'label' => __('Pending'),
                'class' => 'bg-info',
            ],
            'ordered' => [
                'label' => __('lang_v1.ordered'),
                'class' => 'bg-info',
            ],
            'partial' => [
                'label' => __('lang_v1.partial'),
                'class' => 'bg-yellow',
            ],
            'completed' => [
                'label' => __('restaurant.completed'),
                'class' => 'bg-green',
            ],
        ];

        $this->shipping_status_colors = [
            'new' => 'bg-info',
            'ordered' => 'bg-yellow',
            'packed' => 'bg-info',
            'shipped' => 'bg-navy',
            'delivered' => 'bg-green',
            'cancelled' => 'bg-red',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! auth()->user()->can('purchase_order_R.view_all') && ! auth()->user()->can('purchase_order.view_own')) {
            abort(403, 'Unauthorized action.');
        }

        $shipping_statuses = $this->transactionUtil->shipping_statuses();
        $business_id = request()->session()->get('user.business_id');
        if (request()->ajax()) {
            $purchase_orders = Transaction::leftJoin('contacts', 'transactions.contact_id', '=', 'contacts.id')
                    ->join(
                        'business_locations AS BS',
                        'transactions.location_id',
                        '=',
                        'BS.id'
                    )
                    ->leftJoin('purchase_lines as pl', 'transactions.id', '=', 'pl.transaction_id')
                    ->leftJoin('users as u', 'transactions.created_by', '=', 'u.id')
                    ->where('transactions.business_id', $business_id)
                    ->where('transactions.type', 'purchase_order')
                    ->select(
                        'transactions.id',
                        'transactions.transaction_date',
                        'transactions.ref_no',
                        'transactions.po_no',
                        'transactions.status',
                        'transactions.additional_notes',
                        'contacts.name',
                        'contacts.supplier_business_name',
                        'transactions.final_total',
                        'BS.name as location_name',
                        'transactions.shipping_status',
                        DB::raw("CONCAT(COALESCE(u.surname, ''),' ',COALESCE(u.first_name, ''),' ',COALESCE(u.last_name,'')) as added_by"),
                        DB::raw('SUM(pl.quantity - pl.po_quantity_purchased) as po_qty_remaining,COUNT(pl.id) as no_of_items')
                    )
                    ->groupBy('transactions.id');

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $purchase_orders->whereIn('transactions.to_location_id', $permitted_locations);
            }

            if (! empty(request()->location_id)) {
                $purchase_orders->where('transactions.location_id', request()->location_id);
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

            if (! empty(request()->input('shipping_status'))) {
                $purchase_orders->where('transactions.shipping_status', request()->input('shipping_status'));
            }

            return DataTables::of($purchase_orders)
                ->addColumn('action', function ($row){
                    $html = '<div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                                data-toggle="dropdown" aria-expanded="false">'.
                                __('messages.actions').
                                '<span class="caret"></span><span class="sr-only">Toggle Dropdown
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-left" role="menu">';
                    if (auth()->user()->can('purchase_order_R.view_all')) {
                        $html .= '<li><a href="#" data-href="'.action([\App\Http\Controllers\PurchaseOrderRController::class, 'show'], [$row->id]).'" class="btn-modal" data-container=".view_modal"><i class="fas fa-eye" aria-hidden="true"></i>'.__('messages.view').'</a></li>';

                        $html .= '<li><a href="#" class="print-invoice" data-href="'.action([\App\Http\Controllers\PurchaseController::class, 'printInvoice'], [$row->id]).'"><i class="fas fa-print" aria-hidden="true"></i>'.__('messages.print').'</a></li>';
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
                ->editColumn('status', function ($row){
                    $status = '';
                    $order_statuses = $this->purchaseOrderStatuses;
                    if (array_key_exists($row->status, $order_statuses)) {
                        $status = '<span class="label '.$order_statuses[$row->status]['class']
                        .'" >'.$order_statuses[$row->status]['label'].'</span>';
                    }

                    return $status;
                })
                ->editColumn('shipping_status', function ($row) use ($shipping_statuses) {
                    $status_color = ! empty($this->shipping_status_colors[$row->shipping_status]) ? $this->shipping_status_colors[$row->shipping_status] : 'bg-gray';
                    $status = ! empty($row->shipping_status) ? '<a href="#" class="btn-modal" data-href="'.action([\App\Http\Controllers\SellController::class, 'editShipping'], [$row->id]).'" data-container=".view_modal"><span class="label '.$status_color.'">'.$shipping_statuses[$row->shipping_status].'</span></a>' : '';

                    return $status;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return  action([\App\Http\Controllers\PurchaseOrderRController::class, 'show'], [$row->id]);
                    }, ])
                ->rawColumns(['final_total', 'action', 'ref_no', 'name', 'status', 'shipping_status'])
                ->make(true);
        }

        $purchaseOrderStatuses = [];
        foreach ($this->purchaseOrderStatuses as $key => $value) {
            $purchaseOrderStatuses[$key] = $value['label'];
        }

        return view('purchase_order.r-index')->with(compact('purchaseOrderStatuses', 'shipping_statuses'));
    }
}
