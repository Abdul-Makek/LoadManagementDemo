<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LoadModel extends Model
{
    use HasFactory;
    protected $table = 'load_details';

    protected $fillable = [
        'pbs_id',
        'time' ,
        'grid_id',
        'grid_demand',
        'grid_supply'
    ];

    public function isLoadExist($pbs_id, $grid_id){
        return LoadModel::where('pbs_id', '=', $pbs_id)->where('grid_id', '=', $grid_id)->exists();
    }

    public function getLoads($pbs_id, $grid_id, $date){
        return LoadModel::where('pbs_id', '=', $pbs_id)
                ->where('grid_id', '=', $grid_id)
                ->whereDate('time', '=', $date)
                ->orderBy('time', 'asc')
                ->get();
    }

    public function deleteLoad($pbs_id, $grid_id, $load_id){
        return LoadModel::where('pbs_id', '=', $pbs_id)->where('grid_id', $grid_id)->where('id', '=', $load_id)->delete();
    }

    public function getEmptyDataTable($fromDate, $toDate){
        
        if($fromDate->greaterThan($toDate)){
            $tmpDate = $toDate;
            $toDate = $fromDate;
            $fromDate = $tmpDate;
        }
        
        $fromDate = Carbon::parse($fromDate)->startOfDay();
        $toDate = Carbon::parse($toDate)->endOfDay();
        $dataTable = [];
    
        while($toDate->greaterThan($fromDate)){
            $dataTable[$fromDate->format("Y-m-d H:i:s")] = [];
            $fromDate = $fromDate->addHour();
        }

        return $dataTable;
    }

    public function updateLoad($pbs_id, $grid_id, $time, $demand, $supply){
        return LoadModel::updateOrCreate(
            ['pbs_id' => $pbs_id, 'grid_id' => $grid_id,  'time' => $time],
            ['grid_demand' => $demand, 'grid_supply' => $supply]
        );
    }

    public function dailyReport($pbs_id, $report_date){
        return LoadModel::join('grids', 'load_details.grid_id', '=', 'grids.id')
                ->select('load_details.id', 'load_details.grid_id', 'load_details.time', 'load_details.grid_demand', 'load_details.grid_supply', 'grids.name')
                ->where('load_details.pbs_id', '=', $pbs_id)
                ->whereDate('time', '=', $report_date)
                ->orderBy('load_details.grid_id', 'asc')
                ->orderBy('load_details.time', 'asc')
                ->get();
    }

    public function hourlyLoadDetails($pbs_id, $date){
        return LoadModel::where('pbs_id', '=', $pbs_id)
            ->where('time', '=', $date)->get();
    }
}
