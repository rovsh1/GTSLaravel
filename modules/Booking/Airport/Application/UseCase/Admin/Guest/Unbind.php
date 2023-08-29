<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin\Guest;

use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Unbind implements UseCaseInterface
{
    public function execute(int $bookingId, int $guestId): void {}
}
