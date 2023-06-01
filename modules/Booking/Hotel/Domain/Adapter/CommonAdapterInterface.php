<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Adapter;

use Module\Booking\Hotel\Application\Command\Admin\CreateBooking;

interface CommonAdapterInterface
{
    public function createBooking(CreateBooking $request): int;
}
