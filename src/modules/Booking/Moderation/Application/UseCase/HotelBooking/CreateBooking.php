<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking;

use Module\Booking\Moderation\Application\Exception\NotFoundHotelCancelPeriod as ApplicationNotFoundHotelCancelPeriod;
use Module\Booking\Moderation\Application\Factory\HotelBooking\CancelConditionsFactory;
use Module\Booking\Moderation\Application\RequestDto\CreateBookingRequestDto;
use Module\Booking\Moderation\Application\Service\DetailsEditor\DetailsEditorFactory;
use Module\Booking\Moderation\Application\Support\UseCase\AbstractCreateBooking;
use Module\Booking\Moderation\Domain\Booking\Service\HotelBooking\HotelValidator;
use Module\Booking\Shared\Domain\Adapter\AdministratorAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Exception\HotelBooking\NotFoundHotelCancelPeriod;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceId;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CreateBooking extends AbstractCreateBooking
{
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        AdministratorAdapterInterface $administratorAdapter,
        private readonly BookingRepositoryInterface $repository,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly HotelValidator $hotelValidator,
        private readonly DetailsEditorFactory $detailsEditorFactory,
    ) {
        parent::__construct($orderRepository, $administratorAdapter);
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
        $this->administratorAdapter->setBookingAdministrator($booking->id(), $request->administratorId);

        return $booking->id()->value();
    }
}
