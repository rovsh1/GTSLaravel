<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Module\Booking\Moderation\Application\Factory\CancelConditionsFactory;
use Module\Booking\Moderation\Application\RequestDto\CreateBookingRequestDto;
use Module\Booking\Moderation\Application\Service\DetailsEditor\DetailsEditorFactory;
use Module\Booking\Moderation\Application\Support\UseCase\AbstractCreateBooking;
use Module\Booking\Moderation\Domain\Adapter\AdministratorAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceId;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CreateBooking extends AbstractCreateBooking
{
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        AdministratorAdapterInterface $administratorAdapter,
        private readonly BookingRepositoryInterface $repository,
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly CancelConditionsFactory $cancelConditionsFactory,
        private readonly DetailsEditorFactory $detailsEditorFactory,
    ) {
        parent::__construct($orderRepository, $administratorAdapter);
    }

    public function execute(CreateBookingRequestDto $request): int
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
            prices: BookingPrices::createEmpty(CurrencyEnum::UZS, $orderCurrency),//@todo netto валюта
        );
        $editor = $this->detailsEditorFactory->build($booking);
        $editor->create($booking->id(), new ServiceId($request->serviceId), $request->detailsData);
        $this->administratorAdapter->setBookingAdministrator($booking->id(), $request->administratorId);

        return $booking->id()->value();
    }
}
