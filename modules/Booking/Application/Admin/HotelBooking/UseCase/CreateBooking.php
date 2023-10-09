<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\UseCase;

use Module\Booking\Application\Admin\HotelBooking\Exception\NotFoundHotelCancelPeriod as ApplicationException;
use Module\Booking\Application\Admin\HotelBooking\Factory\CancelConditionsFactory;
use Module\Booking\Application\Admin\HotelBooking\Factory\HotelInfoFactory;
use Module\Booking\Application\Admin\HotelBooking\Request\CreateBookingDto;
use Module\Booking\Application\Admin\Shared\Support\UseCase\AbstractCreateBooking;
use Module\Booking\Deprecated\HotelBooking\Adapter\HotelAdapterInterface;
use Module\Booking\Deprecated\HotelBooking\Exception\NotFoundHotelCancelPeriod;
use Module\Booking\Deprecated\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Deprecated\HotelBooking\Service\HotelValidator;
use Module\Booking\Domain\Booking\ValueObject\BookingPeriod;
use Module\Booking\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CreateBooking extends AbstractCreateBooking
{
    public function __construct(
        CommandBusInterface $commandBus,
        private readonly BookingRepositoryInterface $repository,
        private readonly HotelValidator $hotelValidator,
        private readonly HotelAdapterInterface $hotelAdapter,
    ) {
        parent::__construct($commandBus);
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
        $orderCurrency = $request->currency;
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
            price: BookingPrices::createEmpty($hotelCurrency, $orderCurrency),
            quotaProcessingMethod: QuotaProcessingMethodEnum::from($request->quotaProcessingMethod),
        );

        return $booking->id()->value();
    }

}
