<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin;

use Module\Booking\Common\Application\Support\UseCase\Admin\AbstractCreateBooking;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Common\Domain\ValueObject\CreatorId;
use Module\Booking\HotelBooking\Application\Exception\NotFoundHotelCancelPeriod as ApplicationException;
use Module\Booking\HotelBooking\Application\Factory\CancelConditionsFactory;
use Module\Booking\HotelBooking\Application\Factory\HotelInfoFactory;
use Module\Booking\HotelBooking\Application\Request\CreateBookingDto;
use Module\Booking\HotelBooking\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\HotelBooking\Domain\Exception\NotFoundHotelCancelPeriod;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\HotelValidator;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\BookingPeriod;
use Module\Shared\Domain\ValueObject\Id;
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
