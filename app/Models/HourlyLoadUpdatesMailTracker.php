<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HourlyLoadUpdatesMailTracker extends Model
{
    use HasFactory;
    protected $table = 'hourly_load_updates_mail_tracker';

    protected $fillable = [
        'pbs_id',
        'time'
    ];

    public function insertTracker($pbs_id, $hour){
        return HourlyLoadUpdatesMailTracker::insert([
            'pbs_id' => $pbs_id,
            'time' => $hour
        ]);
    }

    public function getUnsendMails(){
        return HourlyLoadUpdatesMailTracker::get();
    }

    public function deleteHourlyMailTracket($id){
        return HourlyLoadUpdatesMailTracker::where('id', '=', $id)->delete();
    }
}
