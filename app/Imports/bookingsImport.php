<?php

namespace App\Imports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class bookingsImport implements ToModel, WithHeadingRow
{
    protected $uploadedBy;

    public function __construct($uploadedBy)
    {
        $this->uploadedBy = $uploadedBy;
    }

    public function model(array $row)
    {
        return new Booking([
            'corporate_id'      => $row['corporate_id'],
            'pickup_location'  => $row['pickup_location'],
            'dropoff_location' => $row['dropoff_location'],
            'vehicle_type'     => $row['vehicle_type'],
            'ride_type'        => $row['ride_type'],
            'scheduled_time'   => $row['scheduled_time'] ?? now(),
            'status'           => 'pending_approval',
            'is_bulk'          => true,
            'uploaded_by'      => $this->uploadedBy,
        ]);
    }
}
