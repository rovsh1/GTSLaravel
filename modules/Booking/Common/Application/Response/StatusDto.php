<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Response;

use Sdk\Module\Foundation\Support\Dto\Dto;

class StatusDto extends Dto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $color,
    ) {}
}
