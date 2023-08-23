<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\UseCase\Admin;

use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class DeleteTourist implements UseCaseInterface
{
    public function execute(int $bookingId, int $touristId): void {}
}
