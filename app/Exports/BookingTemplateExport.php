<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class BookingTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            ['pickup_location', 'dropoff_location', 'vehicle_type', 'ride_type', 'scheduled_time'],
            ['123 Main St', '456 Elm St', 'bike', 'express', '2025-04-20 10:00'],
        ];
    }
}

