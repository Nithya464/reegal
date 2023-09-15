<?php

namespace App\Http\Controllers;

use App\Car;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;

class CarController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('car.view') && !auth()->user()->can('car.create') && !auth()->user()->can('car.update')){
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $cars = Car::whereNull('deleted_at')->where('created_by',$business_id);
            return Datatables::of($cars)
                ->editColumn('id', function ($row) {
                  return str_pad($row->id, 4, '0', STR_PAD_LEFT);
                  
                })
                ->editColumn('load_order_type', function ($row) {
                    return ($row->load_order_type=='1') ? "Fist In Last Out" : "First In First Out";
                  })

                  ->editColumn('status', function ($row) {
                    $status=($row->status=='1') ? 'Active' : 'De-Active';
                    $badge=($row->status=='1') ? 'success' : 'danger';
                    return '<span class="badge badge-success">'.$status.'</span>';
                  })
                  ->addColumn('action', function ($row) {
                        $actionHtml = '';
                        if (Gate::allows('car.update')) {
                            $actionHtml .= '<a href="'.action('App\Http\Controllers\CarController@edit', [$row->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;';
                        }
                        if (Gate::allows('car.delete')) {
                            $actionHtml .= '<button data-href="'.action('App\Http\Controllers\CarController@destroy', [$row->id]).'" class="btn btn-xs btn-danger delete_car_button"><i class="glyphicon glyphicon-trash"></i></button>';
                        }
                        return $actionHtml;
                    })

               
                // ->removeColumn('id')
                ->rawColumns(['action','status'])
                ->make(true);
        }

        return view('car.index');
    }


    public function create(){
        if (! auth()->user()->can('car.create')) {
            abort(403, 'Unauthorized action.');
        }
        return view('car.create');
    }

    public function store(Request $request){
        if (! auth()->user()->can('car.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');
        $data=[
            'car_nick_name'=>$request->car_nick_name,
            'years'=>$request->year,
            'exp_date'=>$request->exp_date,
            'make_by'=>$request->make_by,
            'model'=>$request->model,
            'vin_no'=>$request->vin_no,
            'licence_plate'=>$request->licence_plate,
            'start_mileage'=>$request->start_mileage,
            'load_order_type'=>$request->load_order_type,
            'status'=>$request->status,
            'description'=>$request->description,
            'created_by'=>$business_id,
        ];
        $create=Car::create($data);
        if($create){
            return redirect()->action([\App\Http\Controllers\CarController::class, 'index']);
            // return redirect()->route('car')->with('success', 'Car Created Successfully.');
        }
        return redirect()->back()->with('error', 'Somthing Went Wrong.');
    }

    public function edit($id){
        if (! auth()->user()->can('car.update')) {
            abort(403, 'Unauthorized action.');
        }
       
        $business_id = request()->session()->get('user.business_id');
        $car = Car::where('created_by', $business_id)->where('id', $id)->firstOrFail();
        return view('car.edit')->with(compact('car'));
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('car.update')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $car = Car::where('created_by', $business_id)->where('id', $id)->first();

        $car->car_nick_name=$request->car_nick_name;
        $car->years=$request->year;
        $car->exp_date=$request->exp_date;
        $car->make_by=$request->make_by;
        $car->model=$request->model;
        $car->vin_no=$request->vin_no;
        $car->licence_plate=$request->licence_plate;
        $car->start_mileage=$request->start_mileage;
        $car->load_order_type=$request->load_order_type;
        $car->status=$request->status;
        $car->description=$request->description;
        $update=$car->save();

        if($update){
            return redirect()->action([\App\Http\Controllers\CarController::class, 'index']);
            // return redirect()->route('car')->with('success', 'Car Created Successfully.');
        }
        return redirect()->back()->with('error', 'Somthing Went Wrong.');
    }

    public function destroy($id)
    {
        if (! auth()->user()->can('car.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->user()->business_id;
                $car = Car::where('created_by', $business_id)->findOrFail($id);
                $car->deleted_by = $business_id;
                $car->save(); 
                $car->delete();

                $output = ['success' => true,
                    'msg' => __('Car Deleted Successfully'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());
                $output = ['success' => false,
                    'msg' => __('Something Went Wrong.!!'),
                ];
            }

            return $output;
        }
    }
}
