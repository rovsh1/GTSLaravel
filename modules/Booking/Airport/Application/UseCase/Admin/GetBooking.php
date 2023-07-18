<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin;

use Module\Booking\Airport\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Application\Factory\BookingDtoFactoryInterface;
use Module\Booking\Common\Application\Support\UseCase\Admin\AbstractGetBooking as Base;

class GetBooking extends Base
{
    public function __construct(BookingRepositoryInterface $repository, BookingDtoFactoryInterface $factory)
    {
        parent::__construct($repository, $factory);
    }
}
