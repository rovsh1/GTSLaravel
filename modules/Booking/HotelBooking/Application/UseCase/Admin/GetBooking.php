<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin;

use Module\Booking\Common\Application\Support\UseCase\Admin\AbstractGetBooking as Base;
use Module\Booking\HotelBooking\Application\Factory\BookingDtoFactory;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;

class GetBooking extends Base
{
    public function __construct(BookingRepositoryInterface $repository, BookingDtoFactory $factory)
    {
        parent::__construct($repository, $factory);
    }
}
