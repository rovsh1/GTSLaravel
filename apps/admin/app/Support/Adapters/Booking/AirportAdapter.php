<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Carbon\CarbonInterface;
use Module\Booking\Airport\Application\Request\CreateBookingDto;
use Module\Booking\Airport\Application\UseCase\Admin\CreateBooking;
use Module\Booking\Airport\Application\UseCase\Admin\GetBooking;
use Module\Booking\Airport\Application\UseCase\Admin\GetBookingsByFilters;
use Module\Booking\Airport\Application\UseCase\Admin\Guest\Bind;
use Module\Booking\Airport\Application\UseCase\Admin\Guest\Unbind;

class AirportAdapter
{
    public function getBookings(array $filters = []): mixed
    {
        return app(GetBookingsByFilters::class)->execute($filters);
    }

    public function getBooking(int $id): mixed
    {
        return app(GetBooking::class)->execute($id);
    }

    public function createBooking(
        int $cityId,
        int $clientId,
        ?int $legalId,
        int $currencyId,
        int $airportId,
        int $serviceId,
        CarbonInterface $date,
        int $creatorId,
        ?int $orderId,
        ?string $note = null
    ): int {
        return app(CreateBooking::class)->execute(
            new CreateBookingDto(
                cityId: $cityId,
                creatorId: $creatorId,
                clientId: $clientId,
                legalId: $legalId,
                currencyId: $currencyId,
                airportId: $airportId,
                serviceId: $serviceId,
                orderId: $orderId,
                date: $date,
                note: $note
            )
        );
    }

    public function bindGuest(int $bookingId, int $guestId): void
    {
        app(Bind::class)->execute($bookingId, $guestId);
    }

    public function unbindGuest(int $bookingId, int $guestId): void
    {
        app(Unbind::class)->execute($bookingId, $guestId);
    }
}
