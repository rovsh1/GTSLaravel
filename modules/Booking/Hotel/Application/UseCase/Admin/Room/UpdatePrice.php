<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin\Room;

use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdatePrice implements UseCaseInterface
{
    public function execute(int $bookingId, int $roomBookingId, float|null $boPrice, float|null $hoPrice): void {}
}
