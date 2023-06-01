<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Infrastructure\Adapter;

use Module\Booking\Hotel\Application\Command\Admin\CreateBooking as CreateBookingRequest;
use Module\Booking\Common\Application\Request\CreateBookingDto;
use Module\Booking\Common\Application\UseCase\CreateBooking;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Hotel\Domain\Adapter\CommonAdapterInterface;

class CommonAdapter implements CommonAdapterInterface
{
    public function __construct(
        private readonly CreateBooking $createBookingUseCase,
    ) {}

    public function createBooking(CreateBookingRequest $request): int
    {
        return $this->createBookingUseCase->execute(
            new CreateBookingDto(
                cityId: $request->cityId,
                clientId: $request->clientId,
                hotelId: $request->hotelId,
                period: $request->period,
                creatorId: $request->creatorId,
                orderId: $request->orderId,
                note: $request->note,
                type: BookingTypeEnum::HOTEL,
            )
        );
    }
}
