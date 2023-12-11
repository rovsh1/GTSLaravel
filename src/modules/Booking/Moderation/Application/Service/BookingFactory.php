<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Service;

use Module\Booking\Moderation\Application\Exception\NotFoundHotelCancelPeriodException;
use Module\Booking\Moderation\Application\Exception\NotFoundServiceCancelConditionsException;
use Module\Booking\Moderation\Application\Factory\HotelBooking\CancelConditionsFactory as HotelCancelConditionsFactory;
use Module\Booking\Moderation\Application\RequestDto\CreateBookingRequestDto;
use Module\Booking\Moderation\Application\Service\DetailsEditor\DetailsEditorFactory;
use Module\Booking\Moderation\Domain\Booking\Factory\CancelConditionsFactory;
use Module\Booking\Moderation\Domain\Booking\Service\HotelBooking\HotelValidator;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\DbContext\BookingDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Exception\HotelBooking\NotFoundHotelCancelPeriod;
use Module\Booking\Shared\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Module\Supplier\Moderation\Application\Response\ServiceDto;
use Sdk\Booking\Event\BookingCreated;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Booking\ValueObject\CancelConditions;
use Sdk\Booking\ValueObject\CreatorId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Booking\ValueObject\ServiceId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\Enum\ServiceTypeEnum;

class BookingFactory
{
    public function __construct(
        private readonly OrderFactory $orderFactory,
        private readonly AdministratorAdapterInterface $administratorAdapter,
        private readonly BookingDbContextInterface $bookingDbContext,
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly HotelValidator $hotelValidator,
        private readonly CancelConditionsFactory $cancelConditionsFactory,
        private readonly DetailsEditorFactory $detailsEditorFactory,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    /**
     * @param CreateBookingRequestDto $request
     * @return Booking
     * @throws NotFoundServiceCancelConditionsException
     * @throws NotFoundHotelCancelPeriodException
     * @throws EntityNotFoundException
     * @throws \Throwable
     */
    public function create(CreateBookingRequestDto $request): Booking
    {
        $booking = $request->hotelId !== null
            ? $this->processHotelBooking($request)
            : $this->processServiceBooking($request);

        $serviceOrHotelId = new ServiceId($request->hotelId ?? $request->serviceId);
        $editor = $this->detailsEditorFactory->build($booking->serviceType());
        $editor->create($booking->id(), $serviceOrHotelId, $request->detailsData);

        $this->administratorAdapter->setBookingAdministrator($booking->id(), $request->administratorId);

        //@hack детали создаются отдельно, поэтому событие должно отрабатывать после полного создания брони
        $this->eventDispatcher->dispatch(new BookingCreated($booking));

        return $booking;
    }

    /**
     * @param CreateBookingRequestDto $request
     * @return Booking
     * @throws NotFoundHotelCancelPeriodException
     * @throws \Throwable
     */
    private function processHotelBooking(CreateBookingRequestDto $request): Booking
    {
        $markupSettings = $this->hotelAdapter->getMarkupSettings($request->hotelId);
        $bookingPeriod = $request->detailsData['period'] ?? null;
        if ($bookingPeriod === null) {
            throw new \RuntimeException('Undefined booking period');
        }

        try {
            $this->hotelValidator->validateByDto($markupSettings, $bookingPeriod);
        } catch (NotFoundHotelCancelPeriod $e) {
            throw new NotFoundHotelCancelPeriodException($e);
        }

        $cancelConditions = HotelCancelConditionsFactory::fromDto($markupSettings->cancelPeriods, $bookingPeriod);

        $orderId = $this->getOrderId($request);

        return $this->bookingDbContext->create(
            orderId: $orderId,
            creatorId: new CreatorId($request->creatorId),
            prices: BookingPrices::createEmpty(CurrencyEnum::UZS, $request->currency),//@todo netto валюта
            cancelConditions: $cancelConditions,
            serviceType: ServiceTypeEnum::HOTEL_BOOKING,
            note: $request->note,
        );
    }

    /**
     * @param CreateBookingRequestDto $request
     * @return Booking
     * @throws EntityNotFoundException
     * @throws NotFoundServiceCancelConditionsException
     */
    private function processServiceBooking(CreateBookingRequestDto $request): Booking
    {
        $service = $this->supplierAdapter->findService($request->serviceId);
        if ($service === null) {
            throw new EntityNotFoundException('Service not found');
        }
        $cancelConditions = $this->buildServiceCancelConditions($service, $request->detailsData);

        $orderId = $this->getOrderId($request);

        return $this->bookingDbContext->create(
            orderId: $orderId,
            creatorId: new CreatorId($request->creatorId),
            prices: BookingPrices::createEmpty(CurrencyEnum::UZS, $request->currency),//@todo netto валюта
            cancelConditions: $cancelConditions,
            serviceType: $service->type,
            note: $request->note,
        );
    }

    private function getOrderId(CreateBookingRequestDto $request)
    {
        if ($request->orderId !== null) {
            return new OrderId($request->orderId);
        }
        $order = $this->orderFactory->createFromBookingRequest($request);

        return $order->id();
    }

    /**
     * @param ServiceDto $service
     * @param array $details
     * @return CancelConditions|null
     * @throws NotFoundServiceCancelConditionsException
     */
    private function buildServiceCancelConditions(ServiceDto $service, array $details): ?CancelConditions
    {
        $cancelConditions = $this->cancelConditionsFactory->build(
            new ServiceId($service->id),
            $service->type,
            $details['date'] ?? null
        );
        $isTransferServiceBooking = in_array($service->type, ServiceTypeEnum::getTransferCases());
        if ($cancelConditions === null && !$isTransferServiceBooking) {
            throw new NotFoundServiceCancelConditionsException();
        }

        return $cancelConditions;
    }
}
