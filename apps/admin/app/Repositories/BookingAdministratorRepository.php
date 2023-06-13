<?php

declare(strict_types=1);

namespace App\Admin\Repositories;

use App\Admin\Models\Administrator\Administrator;

class BookingAdministratorRepository
{
    public function create(int $bookingId, int $administratorId): void
    {
        \DB::table('administrator_bookings')->create([
            'booking_id' => $bookingId,
            'administrator_id' => $administratorId
        ]);
    }

    public function update(int $bookingId, int $administratorId): void
    {
        \DB::table('administrator_bookings')->updateOrInsert(
            ['booking_id' => $bookingId],
            ['booking_id' => $bookingId, 'administrator_id' => $administratorId],
        );
    }

    public function get(int $bookingId): ?Administrator
    {
        return Administrator::query()
            ->join('administrator_bookings', 'administrators.id', '=', 'administrator_bookings.administrator_id')
            ->where('administrator_bookings.booking_id', $bookingId)
            ->first();
    }
}
