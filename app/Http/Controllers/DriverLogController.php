<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class DriverLogController extends Controller
{
    public function index()
    {
        $data = [
            [
                'plan_by' => 'dj',
                'planning_date' => '19-07-2023',
                'planning_id' => 12024408,
                'drivers' => 1,
                'stops' => 18,
                'orders' => 25,

            ],
            // Add more rows with static data as needed
        ];

        if (request()->ajax()) {
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '<a href="#" class="btn btn-xs btn-default view-driver" data-planning-id="' . $row['planning_id'] . '" title="View"></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('driver_log.index');
    }
}