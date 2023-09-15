<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AssignedDriverController extends Controller
{
    public function index()
    {
        $data = [
            [
                'assigned_date' => '19-07-2023',
                'driver_name' => 'Driver 1',
                'assigned_orders' => 5,
                'action' => '<button class="btn btn-xs btn-default view-driver-btn" title="View"><i class="fa fa-eye"></i></button>',
            ],
            // Add more rows with static data as needed
        ];

        if (request()->ajax()) {
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return $row['action']; // Simply return the action column as it contains the HTML for the action button
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('assign_driver.index');
    }
}