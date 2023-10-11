<?php

declare(strict_types=1);

namespace Module\Booking\Application\AirportBooking\UseCase\Admin;

use Module\Booking\Application\Admin\Shared\Support\UseCase\UpdateBookingStatus as Base;
use Module\Booking\Deprecated\AirportBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Shared\Service\StatusUpdater;

class UpdateBookingStatus extends Base
{
    public function __construct(BookingRepositoryInterface $repository, StatusUpdater $statusUpdater)
    {
        parent::__construct($repository, $statusUpdater);
    }
}
