<?php

namespace App\CustomClasses;

use Illuminate\Support\Facades\Mail;
use App\Models\LoadModel;
use App\Models\GridModel;
use App\Models\HourlyLoadUpdatesMailTracker;
use App\Models\User;
use App\Models\PBSModel;
use Carbon\Carbon;
use App\Mail\HourlyLoadUpdates;

class SendHourlyMail{

    public function __invoke($current_hour = null){
        $pbs_info = PBSModel::getPBSInfo();
        
        if(is_null($current_hour)){
            $current_hour = Carbon::now()->floorHour()->format("Y-m-d H:i:s");
        }
        
        $grids = GridModel::getAllGrids($pbs_info->id);
        $loads = LoadModel::hourlyLoadDetails($pbs_info->id, $current_hour);
        
        if($grids->count() != $loads->count()){
            HourlyLoadUpdatesMailTracker::insertTracker($pbs_info->id, $current_hour);
            return;
        }

        $usersEmail = User::getAllUserEmailAddress($pbs_info->id);

        $totalDemand = 0;
        $totalSupply = 0;

        foreach($loads as $load){
            $totalDemand += $load->grid_demand;
            $totalSupply += $load->grid_supply;
        }

        $mailInfo = (object)[];

        $mailInfo->pbs_info = $pbs_info;
        $mailInfo->DateAndTime = $current_hour;
        $mailInfo->TotalDemand = $totalDemand;
        $mailInfo->TotalSupply = $totalSupply;
        $mailInfo->TotalLoadshedding = $totalDemand - $totalSupply;

        if($totalDemand > 0){
            $mailInfo->PersentOfLoadshedding = number_format((($totalDemand - $totalSupply)*100)/$totalDemand, 2);
        }else{
            $mailInfo->PersentOfLoadshedding = 0;
        }

        Mail::to($pbs_info->email)
        ->cc($usersEmail)
        ->send(new HourlyLoadUpdates($mailInfo));
    }
}
?>