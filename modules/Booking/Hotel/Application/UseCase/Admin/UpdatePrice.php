<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdatePrice implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository,
        private readonly BookingUpdater $bookingUpdater
    ) {}

    public function execute(int $bookingId, float|null $boPrice, float|null $hoPrice): void
    {
        $booking = $this->repository->find($bookingId);

        $booking->setPrice(
            new BookingPrice(
                netValue: $booking->price()->netValue(),
                boValue: $boPrice,
                hoValue: $hoPrice,
                isManual: true
            )
        );

        $this->bookingUpdater->store($booking);
    }
}
