<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Module\Booking\Moderation\Application\RequestDto\CreateBookingRequestDto;
use Module\Booking\Moderation\Application\Service\BookingFactory;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CreateBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingFactory $bookingFactory,
    ) {}

    public function execute(CreateBookingRequestDto $request): int
    {
        $booking = $this->bookingFactory->create($request);

        return $booking->id()->value();
    }
}
