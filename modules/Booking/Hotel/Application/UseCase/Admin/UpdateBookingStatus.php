<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin;

use Module\Booking\Common\Application\Support\UseCase\Admin\UpdateBookingStatus as Base;
use Module\Booking\Common\Domain\Service\StatusUpdater;
use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;

class UpdateBookingStatus extends Base
{
    public function __construct(BookingRepositoryInterface $repository, StatusUpdater $statusUpdater)
    {
        parent::__construct($repository, $statusUpdater);
    }
}
