<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking;

use Module\Booking\Moderation\Application\Exception\NotFoundHotelCancelPeriod as ApplicationNotFoundHotelCancelPeriod;
use Module\Booking\Moderation\Application\Factory\HotelBooking\CancelConditionsFactory;
use Module\Booking\Moderation\Application\RequestDto\CreateBookingRequestDto;
use Module\Booking\Moderation\Application\Service\DetailsEditor\DetailsEditorFactory;
use Module\Booking\Moderation\Application\Support\UseCase\AbstractCreateBooking;
use Module\Booking\Moderation\Domain\Booking\Service\HotelBooking\HotelValidator;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Shared\Domain\Booking\DbContext\BookingDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Exception\HotelBooking\NotFoundHotelCancelPeriod;
use Module\Booking\Shared\Domain\Order\DbContext\OrderDbContextInterface;
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
        OrderDbContextInterface $orderDbContext,
        AdministratorAdapterInterface $administratorAdapter,
        private readonly BookingDbContextInterface $bookingDbContext,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly HotelValidator $hotelValidator,
        private readonly DetailsEditorFactory $detailsEditorFactory,
    ) {
        parent::__construct($orderDbContext, $administratorAdapter);
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
            throw new ApplicationNotFoundHotelCancelPeriod($e);
        }

        $booking = $this->bookingDbContext->create(
            orderId: $orderId,
            creatorId: new CreatorId($request->creatorId),
            prices: BookingPrices::createEmpty(CurrencyEnum::UZS, $orderCurrency),
            cancelConditions: CancelConditionsFactory::fromDto($markupSettings->cancelPeriods, $bookingPeriod),
            serviceType: ServiceTypeEnum::HOTEL_BOOKING,
            note: $request->note,//@todo netto валюта
        );

        $editor = $this->detailsEditorFactory->build($booking->serviceType());
        $editor->create($booking->id(), new ServiceId($request->serviceId), $request->detailsData);

        $this->administratorAdapter->setBookingAdministrator($booking->id(), $request->administratorId);

        return $booking->id()->value();
    }
}
