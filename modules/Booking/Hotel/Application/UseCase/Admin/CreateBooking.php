<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin;

use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Application\Support\UseCase\Admin\AbstractCreateBooking;
use Module\Booking\Hotel\Application\Request\CreateBookingDto;
use Module\Booking\Hotel\Domain\Adapter\HotelAdapterInterface;
use Sdk\Module\Contracts\Bus\CommandBusInterface;

class CreateBooking extends AbstractCreateBooking
{
    public function __construct(
        CommandBusInterface $commandBus,
        BookingRepositoryInterface $repository,
        private readonly HotelAdapterInterface $hotelAdapter,
    ) {
        parent::__construct($commandBus, $repository);
    }

    public function execute(CreateBookingDto $request): int
    {
        $orderId = $this->getOrderIdFromRequest($request);
        $hotelDto = $this->hotelAdapter->findById($request->hotelId);
        $markupSettings = $this->hotelAdapter->getMarkupSettings($request->hotelId);
        $booking = $this->repository->create(
            $orderId,
            $request->creatorId,
            $request->hotelId,
            $request->period,
            $request->note,
            $hotelDto,
            $markupSettings
        );

        return $booking->id()->value();
    }
}
