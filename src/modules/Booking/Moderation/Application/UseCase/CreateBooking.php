<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Module\Booking\Moderation\Application\Exception\NotFoundServiceCancelConditionsException;
use Module\Booking\Moderation\Application\RequestDto\CreateBookingRequestDto;
use Module\Booking\Moderation\Application\Service\DetailsEditor\DetailsEditorFactory;
use Module\Booking\Moderation\Application\Support\UseCase\AbstractCreateBooking;
use Module\Booking\Moderation\Domain\Booking\Factory\CancelConditionsFactory;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Booking\ValueObject\CreatorId;
use Sdk\Booking\ValueObject\ServiceId;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\Enum\ServiceTypeEnum;

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
        $cancelConditions = $this->cancelConditionsFactory->build(
            new ServiceId($service->id),
            $service->type,
            $request->detailsData['date'] ?? null
        );
        $isTransferServiceBooking = in_array($service->type, ServiceTypeEnum::getTransferCases());
        if ($cancelConditions === null && !$isTransferServiceBooking) {
            throw new NotFoundServiceCancelConditionsException();
        }
        $booking = $this->repository->create(
            orderId: $orderId,
            creatorId: new CreatorId($request->creatorId),
            prices: BookingPrices::createEmpty(CurrencyEnum::UZS, $orderCurrency),
            cancelConditions: $cancelConditions,
            serviceType: $service->type,
            note: $request->note,//@todo netto валюта
        );
        $editor = $this->detailsEditorFactory->build($booking->serviceType());
        $editor->create($booking->id(), new ServiceId($request->serviceId), $request->detailsData);

        $this->administratorAdapter->setBookingAdministrator($booking->id(), $request->administratorId);

        return $booking->id()->value();
    }
}
