<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GridModel;
use App\Models\LoadModel;
use Illuminate\Support\Facades\Validator;

class GridController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(){
        
        $grids = GridModel::getGrids(session('pbs_info')->id);
        return view('Grid.index', ['grids' => $grids]);
    }

    public function store(Request $request) {
        $rules = array('grid_name' => 'required|string|alpha_dash:ascii|max:80|min:3');
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        $grid = new GridModel();
        $grid->pbs_id = session('pbs_info')->id;
        $grid->name = $request->grid_name;

        $grid->save();
        return response()->json(array(
            'success' => true,
            'errors' => "",
            'msg' => "Grid added successfully.",
        ));
    }

    public function update(Request $request) {
        $rules = array('grid_name' => 'required|string|alpha_dash:ascii|max:80|min:3');
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        if(GridModel::updateGrid(1, $request->grid_id, $request->grid_name)){
            return response()->json(array(
                'success' => true,
                'errors' => "",
                'msg' => "Grid update successfully.",
            ));
        }

        return response()->json(array(
            'success' => false,
            'errors' => "",
            'msg' => "Grid not found.",
        ));
    }

    public function delete(Request $request){
        $grid_id = $request->grid_id;

        $returnObj['status'] = '';
        $returnObj['msg'] = '';
        
        if(!LoadModel::isLoadExist(1, $grid_id)){
            if(GridModel::deleteGrid(1, $grid_id) > 0){
                $returnObj['status'] = 'success';
                $returnObj['msg'] = 'Grid deleted successfully.';
            }else{
                $returnObj['status'] = 'alert';
                $returnObj['msg'] = 'Grid not found.';
            }    
        }else{
            $returnObj['status'] = 'alert';
            $returnObj['msg'] = 'Please delete all the load of this grid first.';
        }
        
        return response()->json($returnObj);
    }
}
