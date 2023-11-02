<?php

declare(strict_types=1);

namespace Module\Booking\Application\UseCase\Admin\HotelBooking;

use Module\Booking\Application\Exception\NotFoundHotelCancelPeriod;
use Module\Booking\Application\Factory\HotelBooking\CancelConditionsFactory;
use Module\Booking\Application\RequestDto\CreateBookingRequestDto;
use Module\Booking\Application\Service\DetailsEditor\DetailsEditorFactory;
use Module\Booking\Application\Support\UseCase\AbstractCreateBooking;
use Module\Booking\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\Service\HotelBooking\HotelValidator;
use Module\Booking\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Domain\Booking\ValueObject\ServiceId;
use Module\Booking\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CreateBooking extends AbstractCreateBooking
{
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        private readonly BookingRepositoryInterface $repository,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly HotelValidator $hotelValidator,
        private readonly DetailsEditorFactory $detailsEditorFactory,
    ) {
        parent::__construct($orderRepository);
    }

    public function execute(CreateBookingRequestDto $request): int
    {
        $orderId = $this->getOrderIdFromRequest($request);
        $orderCurrency = $request->currency;
        if ($orderCurrency === null) {
            throw new EntityNotFoundException('Currency not found');
        }
        $markupSettings = $this->hotelAdapter->getMarkupSettings($request->serviceId);
        $bookingPeriod = $request->detailsData['period'] ?? null;
        if ($bookingPeriod === null) {
            throw new \RuntimeException('Undefined booking period');
        }
        try {
            $this->hotelValidator->validateByDto($markupSettings, $bookingPeriod);
        } catch (NotFoundHotelCancelPeriod $e) {
            throw new NotFoundHotelCancelPeriod($e);
        }
        $booking = $this->repository->create(
            orderId: $orderId,
            creatorId: new CreatorId($request->creatorId),
            prices: BookingPrices::createEmpty(CurrencyEnum::UZS, $orderCurrency),
            cancelConditions: CancelConditionsFactory::fromDto($markupSettings->cancelPeriods, $bookingPeriod),
            serviceType: ServiceTypeEnum::HOTEL_BOOKING,
            note: $request->note,//@todo netto валюта
        );
        $editor = $this->detailsEditorFactory->build($booking);
        $editor->create($booking->id(), new ServiceId($request->serviceId), $request->detailsData);

        return $booking->id()->value();
    }
}
