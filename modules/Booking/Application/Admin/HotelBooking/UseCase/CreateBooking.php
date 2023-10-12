<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\UseCase;

use Module\Booking\Application\Admin\Booking\Request\CreateBookingRequestDto;
use Module\Booking\Application\Admin\HotelBooking\Exception\NotFoundHotelCancelPeriod;
use Module\Booking\Application\Admin\HotelBooking\Exception\NotFoundHotelCancelPeriod as ApplicationException;
use Module\Booking\Application\Admin\HotelBooking\Factory\CancelConditionsFactory;
use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor\DetailsEditorFactory;
use Module\Booking\Application\Admin\Shared\Support\UseCase\AbstractCreateBooking;
use Module\Booking\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\Service\HotelBooking\HotelValidator;
use Module\Booking\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Domain\Booking\ValueObject\ServiceId;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CreateBooking extends AbstractCreateBooking
{
    public function __construct(
        CommandBusInterface $commandBus,
        private readonly BookingRepositoryInterface $repository,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly HotelValidator $hotelValidator,
        private readonly DetailsEditorFactory $detailsEditorFactory,
    ) {
        parent::__construct($commandBus);
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
            throw new ApplicationException($e);
        }
        $booking = $this->repository->create(
            orderId: $orderId,
            creatorId: new CreatorId($request->creatorId),
            cancelConditions: CancelConditionsFactory::fromDto($markupSettings->cancelPeriods, $bookingPeriod),
            note: $request->note,
            serviceType: ServiceTypeEnum::HOTEL_BOOKING,
            prices: BookingPrices::createEmpty(CurrencyEnum::UZS, $orderCurrency),//@todo netto валюта
        );
        $editor = $this->detailsEditorFactory->build($booking);
        $editor->create($booking->id(), new ServiceId($request->serviceId), $request->detailsData);

        return $booking->id()->value();
    }
}
