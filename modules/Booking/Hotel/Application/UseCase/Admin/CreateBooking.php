<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin;

use Module\Booking\Common\Application\Support\UseCase\Admin\AbstractCreateBooking;
use Module\Booking\Hotel\Application\Factory\CancelConditionsFactory;
use Module\Booking\Hotel\Application\Factory\HotelInfoFactory;
use Module\Booking\Hotel\Application\Request\CreateBookingDto;
use Module\Booking\Hotel\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Hotel\Domain\ValueObject\Details\BookingPeriod;
use Module\Shared\Domain\ValueObject\Id;
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
            orderId: new Id($orderId),
            creatorId: new Id($request->creatorId),
            hotelInfo: HotelInfoFactory::fromDto($hotelDto),
            period: BookingPeriod::fromCarbon($request->period),
            note: $request->note,
            cancelConditions: CancelConditionsFactory::fromDto($markupSettings->cancelPeriods, $request->period)
        );

        return $booking->id()->value();
    }

}
