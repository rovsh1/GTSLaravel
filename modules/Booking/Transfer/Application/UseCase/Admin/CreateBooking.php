<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Application\UseCase\Admin;

use Module\Booking\Common\Application\Support\UseCase\Admin\AbstractCreateBooking;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Common\Domain\ValueObject\CreatorId;
use Module\Booking\Transfer\Application\Factory\CancelConditionsFactory;
use Module\Booking\Transfer\Application\Request\CreateBookingDto;
use Module\Booking\Transfer\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CreateBooking extends AbstractCreateBooking
{
    public function __construct(
        CommandBusInterface $commandBus,
        BookingRepositoryInterface $repository,
        private readonly CancelConditionsFactory $cancelConditionsFactory
    ) {
        parent::__construct($commandBus, $repository);
    }

    public function execute(CreateBookingDto $request): int
    {
        $orderId = $this->getOrderIdFromRequest($request);
        $orderCurrency = CurrencyEnum::fromId($request->currencyId);
        if ($orderCurrency === null) {
            throw new EntityNotFoundException('Currency not found');
        }
        $cancelConditions = $this->cancelConditionsFactory->build();
        $booking = $this->repository->create(
            orderId: $orderId,
            creatorId: new CreatorId($request->creatorId),
            serviceId: $request->serviceId,
            cityId: $request->cityId,
            cancelConditions: $cancelConditions,
            note: $request->note,
            price: BookingPrice::createEmpty(CurrencyEnum::UZS, $orderCurrency),//@todo netto валюта
        );

        return $booking->id()->value();
    }
}