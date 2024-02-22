<?php

declare(strict_types=1);

namespace Module\Administrator\Infrastructure\Repository;

use Illuminate\Support\Facades\DB;
use Module\Administrator\Domain\Repository\BookingAdministratorRepositoryInterface;
use Module\Administrator\Domain\ValueObject\AdministratorId;
use Sdk\Booking\ValueObject\BookingId;

class BookingAdministratorRepository implements BookingAdministratorRepositoryInterface
{
    public function set(BookingId $bookingId, AdministratorId $administratorId): void
    {
        DB::table('administrator_bookings')->updateOrInsert(
            ['booking_id' => $bookingId->value()],
            ['booking_id' => $bookingId->value(), 'administrator_id' => $administratorId->value()],
        );
    }
}
