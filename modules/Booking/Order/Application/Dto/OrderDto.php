<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\Dto;

use Custom\Framework\Foundation\Support\Dto\Dto;

class OrderDto extends Dto
{
    public function __construct(
        public readonly int $id,
    ) {}
}
