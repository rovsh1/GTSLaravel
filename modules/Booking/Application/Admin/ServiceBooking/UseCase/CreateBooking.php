<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\UseCase;

use Module\Booking\Application\Admin\ServiceBooking\Factory\CancelConditionsFactory;
use Module\Booking\Application\Admin\ServiceBooking\Request\CreateBookingDto;
use Module\Booking\Application\Admin\Shared\Support\UseCase\AbstractCreateBooking;
use Module\Booking\Domain\ServiceBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
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
        $orderCurrency = $request->currency;
        if ($orderCurrency === null) {
            throw new EntityNotFoundException('Currency not found');
        }
        $cancelConditions = $this->cancelConditionsFactory->build($request->serviceType);
        $bookingId = $this->repository->create(
            orderId: $orderId,
            creatorId: new CreatorId($request->creatorId),
            cancelConditions: $cancelConditions,
            note: $request->note,
            serviceType: $request->serviceType,
            price: BookingPrice::createEmpty(CurrencyEnum::UZS, $orderCurrency),//@todo netto валюта
        );

        return $bookingId->value();
    }
}
