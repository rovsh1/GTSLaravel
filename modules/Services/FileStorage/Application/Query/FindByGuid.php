<?php

namespace Module\Services\FileStorage\Application\Query;

use Custom\Framework\Contracts\Bus\QueryInterface;

class FindByGuid implements QueryInterface
{
    public function __construct(
        public readonly string $guid,
        public readonly ?int $entityId,
        public readonly ?string $name,
        public readonly ?string $contents
    ) {}
}
