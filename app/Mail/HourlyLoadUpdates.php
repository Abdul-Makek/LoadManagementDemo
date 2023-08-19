<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class HourlyLoadUpdates extends Mailable
{
    use Queueable, SerializesModels;

    public $mailDetails;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailInfo)
    {
        $this->mailDetails = $mailInfo; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Hourly Load Status for ". Carbon::parse($this->mailDetails->DateAndTime)->format("Y-m-d H:i:s").".";

        return $this->subject($subject)->markdown('mails.hourlyLoadUpdates');
    }
}
