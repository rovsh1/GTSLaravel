<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin;

use Module\Booking\Airport\Application\Dto\BookingDto;
use Module\Booking\Airport\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Application\Support\UseCase\Admin\AbstractGetBooking as Base;

class GetBooking extends Base
{
    public function __construct(BookingRepositoryInterface $repository)
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
