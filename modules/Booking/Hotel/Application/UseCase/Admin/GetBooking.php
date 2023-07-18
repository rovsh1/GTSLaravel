<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin;

use Module\Booking\Common\Application\Factory\BookingDtoFactoryInterface;
use Module\Booking\Common\Application\Support\UseCase\Admin\AbstractGetBooking as Base;
use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;

class GetBooking extends Base
{
    public function __construct(BookingRepositoryInterface $repository, BookingDtoFactoryInterface $factory)
    {
        parent::__construct($repository, $factory);
    }
}
