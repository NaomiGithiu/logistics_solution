@component('mail::message')
# Trip Rejected

Your trip request #{{ $booking->id }} has been rejected.

**Reason for rejection:**  
{{ $booking->rejection_reason }}

**Pickup Location:** {{ $booking->pickup_location }}  
**Dropoff Location:** {{ $booking->dropoff_location }}  
**Scheduled Time:** {{ $booking->scheduled_time->format('M d, Y H:i') }}  

@component('mail::button', ['url' => route('customer.dashboard')])
Return to Dashboard
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent