<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GridModel extends Model
{
    use HasFactory;
    protected $table = 'grids';

    protected $fillable = [
        'pbs_id',
        'name' ,
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getGrids($pbs_id){
        return GridModel::where('pbs_id', '=', $pbs_id)->orderBy('id', 'desc')->paginate(12);
    }

    public function getAllGrids($pbs_id){
        return GridModel::where('pbs_id', '=', $pbs_id)->orderBy('id', 'asc')->get();
    }

    public function deleteGrid($pbs_id, $grid_id){
        return GridModel::where('pbs_id', '=', $pbs_id)->where('id', '=', $grid_id)->delete();
    }

    public function updateGrid($pbs_id, $grid_id, $grid_name){
        return GridModel::where('pbs_id', '=', $pbs_id)->where('id', '=', $grid_id)->update(['name' => $grid_name]);
    }
}
