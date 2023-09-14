<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin;

use Module\Booking\Airport\Application\Request\CreateBookingDto;
use Module\Booking\Airport\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Application\Support\UseCase\Admin\AbstractCreateBooking;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Contracts\Bus\CommandBusInterface;

class CreateBooking extends AbstractCreateBooking
{
    public function __construct(
        CommandBusInterface $commandBus,
        BookingRepositoryInterface $repository,
    ) {
        parent::__construct($commandBus, $repository);
    }

    public function execute(CreateBookingDto $request): int
    {
        $orderId = $this->getOrderIdFromRequest($request);
        $currency = CurrencyEnum::fromId($request->currencyId);
        $booking = $this->repository->create(
            $orderId,
            $currency,
            $request->creatorId,
            $request->serviceId,
            $request->airportId,
            $request->date,
            $request->note,
        );

        return $booking->id()->value();
    }
}
