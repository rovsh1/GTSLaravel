<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\Response;

use Module\Shared\Enum\ServiceTypeEnum;

class ServiceDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly ServiceTypeEnum $type,
    ) {}
}