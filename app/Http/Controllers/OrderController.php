<?php

namespace App\Http\Controllers;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){
        

        if (!auth()->user()->can('order.view')){
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $orders = [
               [
                  'action' => '',
                  'status' => 1,
                  'order_no' => 'OD_0001',
                  'order_date' => '2023-07-20',
                  'sales_person' => 'Jack',
                  'customer' => 'John Mareque',
                  'payable' => '100',
                  'products' => 'Breads',
                  'credit_memo' => '1',
                  'packed_boxes' => '5',
                  'shipping_type' => 'ODR Shipped',
                  'delivery_date' => '2023-07-25',
               ],
            ];
            return Datatables::of($orders)
                ->editColumn('action', function ($row) {
                    return "<i class='fa fa-eye'></i> <i class='fa fa-history'></i> <i class='fa fa-print'></i> <i class='fa fa-truck'></i> <i class='fa fa-envelope'></i> <i class='fa fa-edit'></i> <i class='fa fa-plus'></i> <i class='fa fa-pencil'></i>";
                })
                ->rawColumns([])
                ->make(true);
        }

        return view('order.index');
    }
}