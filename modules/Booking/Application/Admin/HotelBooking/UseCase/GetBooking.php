<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\UseCase;

use Module\Booking\Application\Admin\HotelBooking\Factory\BookingDtoFactory;
use Module\Booking\Application\Admin\Shared\Support\UseCase\AbstractGetBooking as Base;
use Module\Booking\Deprecated\HotelBooking\Repository\BookingRepositoryInterface;

class GetBooking extends Base
{
    public function __construct(BookingRepositoryInterface $repository, BookingDtoFactory $factory)
    {
        parent::__construct($repository, $factory);
    }
}
