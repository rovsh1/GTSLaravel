<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin;

use Module\Booking\Common\Application\Support\UseCase\Admin\AbstractCreateBooking;
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
        $markupSettings = $this->hotelAdapter->getMarkupSettings($request->hotelId);
        try {
            $this->hotelValidator->validateByDto($markupSettings, $request->period);
        } catch (NotFoundHotelCancelPeriod $e) {
            throw new ApplicationException($e);
        }
        $booking = $this->repository->create(
            orderId: $orderId,
            creatorId: new Id($request->creatorId),
            hotelInfo: HotelInfoFactory::fromDto($hotelDto),
            period: BookingPeriod::fromCarbon($request->period),
            note: $request->note,
            cancelConditions: CancelConditionsFactory::fromDto($markupSettings->cancelPeriods, $request->period),
            quotaProcessingMethod: QuotaProcessingMethodEnum::from($request->quotaProcessingMethod),
        );

        return $booking->id()->value();
    }

}
