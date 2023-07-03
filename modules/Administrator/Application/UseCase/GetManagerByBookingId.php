<?php

declare(strict_types=1);

namespace Module\Administrator\Application\UseCase;

use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetManagerByBookingId implements UseCaseInterface
{
    public function execute(int $id): mixed {}
}
