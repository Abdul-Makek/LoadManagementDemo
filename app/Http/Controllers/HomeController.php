<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PBSModel;
use App\CustomClasses\SendHourlyMail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        $pbs_info = PBSModel::getPBSInfo();

        if($pbs_info->count() <= 0){
            return redirect("login")->with(Auth::logout());
        }

        if(is_null(session('pbs_info')) || empty(session('pbs_id'))){
            session(['pbs_info' => $pbs_info]);
        }

        return redirect()->route('grid');
    }
}
