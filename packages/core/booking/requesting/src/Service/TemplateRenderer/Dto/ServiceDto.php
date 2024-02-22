<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Service\TemplateRenderer\Dto;

use Sdk\Shared\Enum\ServiceTypeEnum;

class ServiceDto
{
    public function __construct(
        public readonly string $title,
        public readonly string $typeName,
        public readonly ServiceTypeEnum $typeId,
    ) {}
}
