<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\UseCase;

use Module\Booking\Common\Domain\Entity\Booking;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetBookingsByFilters implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository
    ) {}

    public function execute(array $filters = []): mixed
    {
        //@todo hack временно, пока дергаю только из админки
        return \Module\Booking\Common\Infrastructure\Models\Booking::withHotelDetails()->get();
    }
}
