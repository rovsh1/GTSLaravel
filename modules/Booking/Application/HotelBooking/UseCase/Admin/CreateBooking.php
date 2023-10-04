<?php

declare(strict_types=1);

namespace Module\Booking\Application\HotelBooking\UseCase\Admin;

use Module\Booking\Application\HotelBooking\Exception\NotFoundHotelCancelPeriod as ApplicationException;
use Module\Booking\Application\HotelBooking\Factory\CancelConditionsFactory;
use Module\Booking\Application\HotelBooking\Factory\HotelInfoFactory;
use Module\Booking\Application\HotelBooking\Request\CreateBookingDto;
use Module\Booking\Application\Shared\Support\UseCase\Admin\AbstractCreateBooking;
use Module\Booking\Domain\HotelBooking\Adapter\HotelAdapterInterface;
use Module\Booking\Domain\HotelBooking\Exception\NotFoundHotelCancelPeriod;
use Module\Booking\Domain\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\HotelBooking\Service\HotelValidator;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\BookingPeriod;
use Module\Booking\Domain\Shared\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CreateBooking extends AbstractCreateBooking
{
    public function __construct(
        CommandBusInterface $commandBus,
        BookingRepositoryInterface $repository,
        private readonly HotelValidator $hotelValidator,
        private readonly HotelAdapterInterface $hotelAdapter,
    ) {
        parent::__construct($commandBus, $repository);
    }

    public function execute(CreateBookingDto $request): int
    {
        $orderId = $this->getOrderIdFromRequest($request);
        $hotelDto = $this->hotelAdapter->findById($request->hotelId);
        if ($hotelDto === null) {
            throw new EntityNotFoundException('Hotel not found');
        }
        $hotelCurrency = CurrencyEnum::from($hotelDto->currency);
        $markupSettings = $this->hotelAdapter->getMarkupSettings($request->hotelId);
        $orderCurrency = CurrencyEnum::fromId($request->currencyId);
        if ($orderCurrency === null) {
            throw new EntityNotFoundException('Currency not found');
        }
        try {
            $this->hotelValidator->validateByDto($markupSettings, $request->period);
        } catch (NotFoundHotelCancelPeriod $e) {
            throw new ApplicationException($e);
        }
        $booking = $this->repository->create(
            orderId: $orderId,
            creatorId: new CreatorId($request->creatorId),
            hotelInfo: HotelInfoFactory::fromDto($hotelDto),
            period: BookingPeriod::fromCarbon($request->period),
            note: $request->note,
            cancelConditions: CancelConditionsFactory::fromDto($markupSettings->cancelPeriods, $request->period),
            price: BookingPrice::createEmpty($hotelCurrency, $orderCurrency),
            quotaProcessingMethod: QuotaProcessingMethodEnum::from($request->quotaProcessingMethod),
        );

        return $booking->id()->value();
    }

}