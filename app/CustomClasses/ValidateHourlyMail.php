<?php

namespace App\CustomClasses;

class ValidateHourlyMail{

    public function __invoke(){
        
        $unsendMails = HourlyLoadUpdatesMailTracker::getUnsendMails();
        
        if($unsendMails->count()){
            $sendHourlyMail = new SendHourlyMail();
        
            foreach($unsendMails as $unsendMail){
                $loadHour = Carbon::parse($unsendMail->time)->floorHour()->format("Y-m-d H:i:s");

                $sendHourlyMail->__invoke($loadHour);
                
                HourlyLoadUpdatesMailTracker::deleteHourlyMailTracket($unsendMail->id);
            }
        }
    }
}
?>