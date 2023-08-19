<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GridModel;
use App\Models\LoadModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class LoadController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $grids = GridModel::getAllGrids(session('pbs_info')->id);
        $today = Carbon::now()->format('Y-m-d');
        $loads = LoadModel::GetLoads(session('pbs_info')->id, $grids->first()->id, $today);

        $dataTable = LoadModel::getEmptyDataTable(Carbon::now(), Carbon::now());

        foreach($dataTable as $key => $value){
            $currentLoad = $loads->where('time', $key);
            
            if($currentLoad->count()==0){
                $emptyObj = (object) array("id" => "-", "grid_id" => "-", "grid_demand" => "-", "grid_supply" => "-", "grid_loadshedding" => "-");
                $dataTable[$key] =  $emptyObj;
            }else{
                $emptyObj;

                foreach($currentLoad as $row){
                    $emptyObj = (object) array("id" => $row->id, "grid_id" => $row->grid_id, "grid_demand" => $row->grid_demand, "grid_supply" => $row->grid_supply);
                    break;
                }

                $dataTable[$key] = $emptyObj;
            }
        }
        return view('Load.index', ['grids' => $grids, 'loads' => $dataTable, 'date' => $today]);
    }

    public function getLoadDetails(Request $request){

        $loadDate = Carbon::parse($request->load_date);
        $grid_id = $request->grid_id;
        $loads = LoadModel::GetLoads(session('pbs_info')->id, $grid_id, $loadDate->format('Y-m-d'));

        
        $dataTable = LoadModel::getEmptyDataTable($loadDate, $loadDate);

        foreach($dataTable as $key => $value){
            $currentLoad = $loads->where('time', $key);
            
            if($currentLoad->count()==0){
                $emptyObj = (object) array("id" => "-", "grid_id" => "-", "grid_demand" => "-", "grid_supply" => "-", "grid_loadshedding" => "-");
                $dataTable[$key] =  $emptyObj;
            }else{
                $emptyObj;

                foreach($currentLoad as $row){
                    $emptyObj = (object) array("id" => $row->id, "grid_id" => $row->grid_id, "grid_demand" => $row->grid_demand, "grid_supply" => $row->grid_supply);
                    break;
                }

                $dataTable[$key] = $emptyObj;
            }
        }
        return view('Load.loadDetails', ['loads' => $dataTable]);
    }

    public function update(Request $request){
        $rules = array(
            'time' => 'required',
            'load_demand' => 'required|integer|gte:load_supply|min:0',
            'load_supply' => 'required|integer|lte:load_demand|min:0',
             'grid_id' => 'required|exists:grids,id'
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){
             return response()->json(array(
                 'success' => false,
                 'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        if(LoadModel::updateLoad("1", $request->grid_id, $request->time, $request->load_demand, $request->load_supply)){
            return response()->json(array(
                'success' => true,
                'msg' => 'Load Update successfully.'
            ));
        }

        return response()->json(array(
            'success' => false,
            'msg' => 'An error occur while updating the load.'
        ));
    }

    public function delete(Request $request){
        $load_id = (int) $request->load_id;
        $grid_id = (int) $request->grid_id;

        if($load_id>0 && $grid_id>0){
            if(LoadModel::deleteLoad(session('pbs_info')->id, $grid_id, $load_id)){
                return response()->json(array(
                    'success' => true,
                    'msg' => 'Load deleted successfully.'
                ));
            }
        }

        return response()->json(array(
            'success' => false,
            'msg' => 'Load not founded.',
            'error' => ''
        ));
    }
}
