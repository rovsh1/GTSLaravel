<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Application\UseCase\Admin;

use Module\Booking\Transfer\Application\Factory\BookingDtoFactory;
use Module\Booking\Transfer\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Application\Support\UseCase\Admin\AbstractGetBooking as Base;

class GetBooking extends Base
{
    public function __construct(BookingRepositoryInterface $repository, BookingDtoFactory $factory)
    {
        parent::__construct($repository, $factory);
    }
}
