<?php

declare(strict_types=1);

namespace Module\Booking\Application\HotelBooking\UseCase\Admin;

use Module\Booking\Application\HotelBooking\Factory\BookingDtoFactory;
use Module\Booking\Application\Shared\Support\UseCase\Admin\AbstractGetBooking as Base;
use Module\Booking\Domain\HotelBooking\Repository\BookingRepositoryInterface;

class GetBooking extends Base
{
    public function __construct(BookingRepositoryInterface $repository, BookingDtoFactory $factory)
    {
        parent::__construct($repository, $factory);
    }
}
