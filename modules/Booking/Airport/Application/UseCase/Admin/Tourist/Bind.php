<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin\Tourist;

use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Bind implements UseCaseInterface
{
    public function execute(int $bookingId, int $touristId): void {}
}
