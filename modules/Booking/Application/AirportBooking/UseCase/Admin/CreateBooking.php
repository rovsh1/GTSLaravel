<?php

declare(strict_types=1);

namespace Module\Booking\Application\AirportBooking\UseCase\Admin;

use Module\Booking\Application\Admin\Shared\Support\UseCase\AbstractCreateBooking;
use Module\Booking\Application\AirportBooking\Factory\CancelConditionsFactory;
use Module\Booking\Application\AirportBooking\Request\CreateBookingDto;
use Module\Booking\Deprecated\AirportBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Deprecated\AirportBooking\ValueObject\Details\AdditionalInfo;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CreateBooking extends AbstractCreateBooking
{
    public function __construct(
        CommandBusInterface $commandBus,
        private readonly BookingRepositoryInterface $repository,
        private readonly CancelConditionsFactory $cancelConditionsFactory
    ) {
        parent::__construct($commandBus);
    }

    public function execute(CreateBookingDto $request): int
    {
        $orderId = $this->getOrderIdFromRequest($request);
        $orderCurrency = CurrencyEnum::fromId($request->currencyId);
        if ($orderCurrency === null) {
            throw new EntityNotFoundException('Currency not found');
        }
        $cancelConditions = $this->cancelConditionsFactory->build($request->date);
        $booking = $this->repository->create(
            orderId: $orderId,
            creatorId: new CreatorId($request->creatorId),
            serviceId: $request->serviceId,
            airportId: $request->airportId,
            date: $request->date,
            additionalInfo: new AdditionalInfo($request->flightNumber),
            cancelConditions: $cancelConditions,
            note: $request->note,
            price: BookingPrice::createEmpty(CurrencyEnum::UZS, $orderCurrency),//@todo netto валюта
        );

        return $booking->id()->value();
    }
}
