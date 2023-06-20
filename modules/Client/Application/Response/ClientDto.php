<?php

declare(strict_types=1);

namespace Module\Client\Application\Response;

final class ClientDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $type,
    ) {
    }
}
