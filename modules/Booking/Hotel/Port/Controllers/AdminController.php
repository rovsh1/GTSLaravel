<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Port\Controllers;

use Carbon\CarbonPeriod;
use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Custom\Framework\Foundation\Exception\EntityNotFoundException;
use Custom\Framework\PortGateway\Request;
use Module\Booking\Hotel\Application\Command\Admin\CreateBooking;
use Module\Booking\Hotel\Application\Dto\BookingDto;
use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Hotel\Infrastructure\Models\Booking;

class AdminController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
        private readonly BookingRepositoryInterface $repository
    ) {}

    public function getBookings(Request $request): mixed
    {
        $request->validate([
            //filters
        ]);

        return Booking::withHotelDetails()->get();
    }

    public function getBooking(Request $request): mixed
    {
        $request->validate([
            'id' => ['required', 'int'],
        ]);

        $booking = $this->repository->find($request->id);
        if ($booking === null) {
            throw new EntityNotFoundException("Booking not found [{$request->id}]");
        }
        return BookingDto::fromDomain($booking);
    }

    public function createBooking(Request $request): int
    {
        $request->validate([
            'cityId' => ['required', 'int'],
            'clientId' => ['required', 'int'],
            'hotelId' => ['required', 'int'],
            'orderId' => ['nullable', 'int'],
            'dateStart' => ['nullable', 'date'],
            'dateEnd' => ['nullable', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        return $this->commandBus->execute(
            new CreateBooking(
                cityId: $request->cityId,
                clientId: $request->clientId,
                hotelId: $request->hotelId,
                period: new CarbonPeriod($request->dateStart, $request->dateEnd),
                orderId: $request->orderId,
                note: $request->note,
            )
        );
    }
}
