<?php

declare(strict_types=1);

namespace Module\Booking\Application\TransferBooking\UseCase\Admin;

use Module\Booking\Application\Shared\Support\UseCase\Admin\UpdateBookingStatus as Base;
use Module\Booking\Domain\Shared\Service\StatusUpdater;
use Module\Booking\Domain\TransferBooking\Repository\BookingRepositoryInterface;

class UpdateBookingStatus extends Base
{
    public function __construct(BookingRepositoryInterface $repository, StatusUpdater $statusUpdater)
    {
        parent::__construct($repository, $statusUpdater);
    }
}