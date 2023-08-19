<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GridModel;
use App\Models\LoadModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function daily(Request $request){
        $report_date = $request->report_date;
        
        $report_date = (is_null($report_date) || empty($report_date)) ? Carbon::now() : Carbon::parse($report_date);

        $grids = GridModel::getAllGrids(session('pbs_info')->id);
        $loads = LoadModel::dailyReport(session('pbs_info')->id, $report_date->format('Y-m-d'));
        $dataTable = LoadModel::getEmptyDataTable($report_date, $report_date);

        foreach($grids as $grid){
            $gridLoad = $loads->where('grid_id', $grid->id);

            foreach($dataTable as $key => $value){
                
                $currentLoad = $gridLoad->where('time', $key);

                if(is_null($currentLoad) || empty($currentLoad) || $currentLoad->count()==0){
                    $emptyArray = array("id" => "-", "grid_id" => "-", "grid_demand" => "-", "grid_supply" => "-", "grid_loadshedding" => "-", "name" => $grid->name);
                    array_push($dataTable[$key], $emptyArray);
                }
                else{
                    $emptyArray;
                    foreach($currentLoad as $row){
                        $emptyArray = array("id" => $row->id, "grid_id" => $row->grid_id, "grid_demand" => $row->grid_demand, 
                            "grid_supply" => $row->grid_supply, "grid_loadshedding" => $row->grid_demand - $row->grid_supply, "name" => $row->name);
                        break;
                    }
                    array_push($dataTable[$key], $emptyArray);
                }              
            }
        }
        
        return view('reports.daily', ['grids' => $grids, 'loads' => $loads, 'dataTable' => $dataTable, 'report_date' => $report_date->format('Y-m-d')]);
    }
}
