@component('mail::message')
# Trip Approved

Your trip request #{{ $booking->id }} has been approved.

**Pickup Location:** {{ $booking->pickup_location }}  
**Dropoff Location:** {{ $booking->dropoff_location }}  
**Scheduled Time:** {{ $booking->scheduled_time->format('M d, Y H:i') }}  

@if($booking->approval_comments)
**Comments:**  
{{ $booking->approval_comments }}
@endif

@component('mail::button', ['url' => route('bookings.details', $booking->id)])
View Trip Details
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent