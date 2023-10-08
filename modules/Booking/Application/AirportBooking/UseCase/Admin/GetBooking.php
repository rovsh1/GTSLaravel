<?php

declare(strict_types=1);

namespace Module\Booking\Application\AirportBooking\UseCase\Admin;

use Module\Booking\Application\Admin\Shared\Support\UseCase\AbstractGetBooking as Base;
use Module\Booking\Application\AirportBooking\Factory\BookingDtoFactory;
use Module\Booking\Domain\AirportBooking\Repository\BookingRepositoryInterface;

class GetBooking extends Base
{
    public function __construct(BookingRepositoryInterface $repository, BookingDtoFactory $factory)
    {
        parent::__construct($repository, $factory);
    }
}
