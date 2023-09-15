<?php

namespace App\Http\Controllers;

use App\Expense;
use App\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class ManageExpenseController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('manage_expense.view')){
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $created_by = request()->session()->get('user.id');
            $expenses = Expense::with('expense_category')->orderBy('id','DESC');
            
            return Datatables::of($expenses)
                ->editColumn('id', function ($row) {
                  return str_pad($row->id, 4, '0', STR_PAD_LEFT);
                  
                })
                ->editColumn('expense_amount', function ($row) {
                    // return round($row->expense_amount);
                    
                    return number_format(round($row->expense_amount,2), 2, '.', '');;
                })
                ->addColumn('category', function ($category) {
                    return $category->expense_category->name;
                })
                ->addColumn('payment_source', function ($row) {
                    
                    return ['1' => "Today's Bank", '2' => 'Bank Of Baroda'][$row->payment_source_id];
                })
                ->editColumn('date', function ($row) {
                    return date('d-m-Y',strtotime($row->date));
                })
                ->editColumn('payment_method', function ($row) {
                    return ucfirst($row->payment_method);
                })
                ->addColumn('action', function ($row) {
                    $actionHtml = '';
                    if (auth()->user()->can("manage_expense.update")) {
                        $actionHtml .= '<a class="btn btn-xs btn-primary" href="'.action('App\Http\Controllers\ManageExpenseController@edit', [$row->id]).'" title="Edit"><i class="glyphicon glyphicon-edit"></i></a> &nbsp;';
                         
                    }
                    if (auth()->user()->can("manage_expense.delete")) {
                        $actionHtml .= '<button data-href="'.action('App\Http\Controllers\ManageExpenseController@destroy', [$row->id]).'" class="btn btn-xs btn-danger delete_expense_button"><i class="glyphicon glyphicon-trash"></i></button>';
                    }
                    return $actionHtml;
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('manage_expense.index');

        
    }

    
    public function create()
    {
        if (! auth()->user()->can('manage_expense.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $expense_categories = ExpenseCategory::where('business_id', $business_id)
        ->whereNull('parent_id')
        ->pluck('name', 'id');
        return view('manage_expense.create')->with(compact('expense_categories'));
    }

    
    public function store(Request $request)
    {
        if (! auth()->user()->can('manage_expense.create')) {
            abort(403, 'Unauthorized action.');
        }

        $inputs = $request->all();
        $expense = new Expense();
        $rules = $expense->rules('create');
        $messages = $expense->rulesMessages('create');
        $validator = Validator::make($inputs,$rules,$messages);

        if($validator->fails()){
            //return Redirect::back()->withInput()->withErrors($validator);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $createData = $expense->prepareCreateData($inputs);
        $create=$expense->create($createData);
        if($create){
            $output = ['success' => 1,
                'msg' => "Expense Added Successfully",
            ];
            return redirect('manage_expenses')->with('status', $output);
        }
        return redirect()->back()->with('error', 'Somthing Went Wrong!!');
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        if (! auth()->user()->can('manage_expense.update')) {
            abort(403, 'Unauthorized action.');
        }

        $created_by =request()->session()->get('user.id');
        $expense = Expense::find($id);
        $business_id = request()->session()->get('user.business_id');
        $expense_categories = ExpenseCategory::where('business_id', $business_id)
        ->whereNull('parent_id')
        ->pluck('name', 'id');
        return view('manage_expense.edit')->with(compact(['expense','expense_categories']));
    }

    
    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('manage_expense.update')) {
            abort(403, 'Unauthorized action.');
        }

        $inputs = $request->all();
        $expense = new Expense();
        $rules = $expense->rules('update');
        $messages = $expense->rulesMessages('update');
        $validator = Validator::make($inputs,$rules,$messages);
        if($validator->fails()){
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $created_by = request()->session()->get('user.id');
        $expenseData = Expense::findOrFail($id);
        $updateData = $expense->prepareUpdateData($inputs,$expenseData);
        $update=$expenseData->update($updateData);
        
        if($update){
            $output = ['success' => 1,
                'msg' => "Expense Updated Successfully",
            ];
            return redirect('manage_expenses')->with('status', $output);
        }
        return redirect()->back()->with('error', 'Somthing Went Wrong!!');
    }

   
    public function destroy($id)
    {
        if (! auth()->user()->can('manage_expense.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $created_by = request()->session()->get('user.id');
                $expense = Expense::findOrFail($id);
                if ($expense) {
                    $expense->deleted_by = request()->session()->get('user.id');
                    $expense->save(); 
                    $expense->delete();
                    $output = ['success' => true,
                        'msg' => __('Expense Deleted Successfully'),
                    ];
                }
                
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => '__("messages.something_went_wrong")',
                ];
            }

            return $output;
        }
    }
}