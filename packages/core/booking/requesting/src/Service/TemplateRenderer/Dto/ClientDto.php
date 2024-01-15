<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Service\TemplateRenderer\Dto;

class ClientDto
{
    public function __construct(
        public readonly string $name,
    ) {}
}
