<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\UseCase;

use Module\Booking\Application\Admin\ServiceBooking\Factory\CancelConditionsFactory;
use Module\Booking\Application\Admin\ServiceBooking\Request\CreateBookingDto;
use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsCreator\DetailsCreator;
use Module\Booking\Application\Admin\Shared\Support\UseCase\AbstractCreateBooking;
use Module\Booking\Domain\ServiceBooking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\ServiceBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingPrice;
use Module\Booking\Domain\ServiceBooking\ValueObject\ServiceId;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CreateBooking extends AbstractCreateBooking
{
    public function __construct(
        CommandBusInterface $commandBus,
        private readonly BookingRepositoryInterface $repository,
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly CancelConditionsFactory $cancelConditionsFactory,
        private readonly DetailsCreator $detailsCreator,
    ) {
        parent::__construct($commandBus);
    }

    public function execute(CreateBookingDto $request): int
    {
        $service = $this->supplierAdapter->findService($request->serviceId);
        if ($service === null) {
            throw new EntityNotFoundException('Service not found');
        }
        $orderId = $this->getOrderIdFromRequest($request);
        $orderCurrency = $request->currency;
        if ($orderCurrency === null) {
            throw new EntityNotFoundException('Currency not found');
        }
        $cancelConditions = $this->cancelConditionsFactory->build($service->type);
        $booking = $this->repository->create(
            orderId: $orderId,
            creatorId: new CreatorId($request->creatorId),
            cancelConditions: $cancelConditions,
            note: $request->note,
            serviceType: $service->type,
            price: BookingPrice::createEmpty(CurrencyEnum::UZS, $orderCurrency),//@todo netto валюта
        );
        $this->detailsCreator->create(
            $booking->id(),
            $service->type,
            new ServiceId($request->serviceId),
            $request->detailsData
        );

        return $booking->id()->value();
    }
}
