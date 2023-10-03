<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Application\UseCase\Admin;

use Module\Booking\Transfer\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Application\Support\UseCase\Admin\UpdateBookingStatus as Base;
use Module\Booking\Common\Domain\Service\StatusUpdater;

class UpdateBookingStatus extends Base
{
    public function __construct(BookingRepositoryInterface $repository, StatusUpdater $statusUpdater)
    {
        parent::__construct($repository, $statusUpdater);
    }
}
