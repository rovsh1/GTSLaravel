<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin;

use Module\Booking\Common\Application\Support\UseCase\Admin\AbstractGetBooking as Base;
use Module\Booking\Hotel\Application\Dto\BookingDto;
use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository;

class GetBooking extends Base
{
    public function __construct(BookingRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @return class-string<BookingDto>
     */
    protected function getDto(): string
    {
        return BookingDto::class;
    }
}
