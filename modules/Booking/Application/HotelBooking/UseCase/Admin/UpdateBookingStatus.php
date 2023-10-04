<?php

declare(strict_types=1);

namespace Module\Booking\Application\HotelBooking\UseCase\Admin;

use Module\Booking\Application\Shared\Support\UseCase\Admin\UpdateBookingStatus as Base;
use Module\Booking\Domain\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Shared\Service\StatusUpdater;

class UpdateBookingStatus extends Base
{
    public function __construct(BookingRepositoryInterface $repository, StatusUpdater $statusUpdater)
    {
        parent::__construct($repository, $statusUpdater);
    }
}