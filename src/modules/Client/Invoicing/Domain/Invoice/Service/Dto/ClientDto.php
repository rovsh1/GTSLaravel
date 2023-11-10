<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Service\Dto;

class ClientDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $phone,
        public readonly string $address,
    ) {}
}
