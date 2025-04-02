<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Booking Confirmation')
                    ->view('emails.booking_confirmation')
                    ->with([
                        'customerName' => $this->booking->customer->first_name,
                        'pickup' => $this->booking->pickup_location,
                        'dropoff' => $this->booking->dropoff_location,
                        'fare' => $this->booking->estimated_fare,
                        'vehicle' => $this->booking->vehicle_type,
                        'time' => $this->booking->scheduled_time ?? 'Immediate',
                    ]);
    }
}
