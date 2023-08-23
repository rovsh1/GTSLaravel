<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Carbon\CarbonInterface;
use Module\Booking\Airport\Application\Request\AddTouristDto;
use Module\Booking\Airport\Application\Request\CreateBookingDto;
use Module\Booking\Airport\Application\Request\UpdateTouristDto;
use Module\Booking\Airport\Application\UseCase\Admin\AddTourist;
use Module\Booking\Airport\Application\UseCase\Admin\CreateBooking;
use Module\Booking\Airport\Application\UseCase\Admin\DeleteTourist;
use Module\Booking\Airport\Application\UseCase\Admin\GetBooking;
use Module\Booking\Airport\Application\UseCase\Admin\GetBookingsByFilters;
use Module\Booking\Airport\Application\UseCase\Admin\UpdateTourist;

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

    public function addTourist(
        int $bookingId,
        string $fullName,
        int $countryId,
        int $gender,
        bool $isAdult,
        ?int $age
    ): void {
        app(AddTourist::class)->execute(
            new AddTouristDto(
                bookingId: $bookingId,
                fullName: $fullName,
                countryId: $countryId,
                gender: $gender,
                isAdult: $isAdult,
                age: $age
            )
        );
    }

    public function updateTourist(
        int $bookingId,
        int $touristId,
        string $fullName,
        int $countryId,
        int $gender,
        bool $isAdult,
        ?int $age
    ): void {
        app(UpdateTourist::class)->execute(
            new UpdateTouristDto(
                bookingId: $bookingId,
                touristId: $touristId,
                fullName: $fullName,
                countryId: $countryId,
                age: $age,
                gender: $gender,
                isAdult: $isAdult
            )
        );
    }

    public function deleteTourist(int $bookingId, int $touristId): void
    {
        app(DeleteTourist::class)->execute($bookingId, $touristId);
    }
}
