@component('mail::message')
# Load Status {{ Carbon\Carbon::parse($mailDetails->DateAndTime)->format("Y-m-d H:i:s") }}
 
Dear sir,

The load status of {{ $mailDetails->DateAndTime }} for {{ $mailDetails->pbs_info->name }} is followings:

@component('mail::table')
| Details               | Value                                    |
| ----------------------|:----------------------------------------:|
| Total Demand          | {{ $mailDetails->TotalDemand }}          |
| Total Supply          | {{ $mailDetails->TotalSupply }}          |
| Total Loadshedding    | {{ $mailDetails->TotalLoadshedding }}    |
| PersentOfLoadshedding | {{ $mailDetails->PersentOfLoadshedding }}|
@endcomponent
 
Thanks,<br>
{{ config('app.name') }}
@endcomponent