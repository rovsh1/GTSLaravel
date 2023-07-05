<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Hotel\Domain\ValueObject\ManualChangablePrice;
use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetManualPrice implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository,
        private readonly BookingUpdater $bookingUpdater
    ) {}

    public function execute(int $bookingId, float|null $boPrice, float|null $hoPrice): void
    {
        //@todo отдельные методы на установку bo/ho и очистку
        $booking = $this->repository->find($bookingId);

        $booking->setPrice(
            new BookingPrice(
                netValue: $booking->price()->netValue(),
                boPrice: new ManualChangablePrice($boPrice, true),
                hoPrice: new ManualChangablePrice($hoPrice, true),
            )
        );

        $this->bookingUpdater->store($booking);
    }
}
