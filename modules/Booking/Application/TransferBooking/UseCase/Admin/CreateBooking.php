<?php

declare(strict_types=1);

namespace Module\Booking\Application\TransferBooking\UseCase\Admin;

use Module\Booking\Application\Admin\Shared\Support\UseCase\AbstractCreateBooking;
use Module\Booking\Application\TransferBooking\Factory\CancelConditionsFactory;
use Module\Booking\Application\TransferBooking\Request\CreateBookingDto;
use Module\Booking\Domain\Shared\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Booking\Domain\TransferBooking\Repository\BookingRepositoryInterface;
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
