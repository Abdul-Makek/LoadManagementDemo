<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PBSModel extends Model
{
    use HasFactory;
    protected $table = 'pbs_info';

    public function getPBSInfo(){
        return PBSModel::first();
    }
}
