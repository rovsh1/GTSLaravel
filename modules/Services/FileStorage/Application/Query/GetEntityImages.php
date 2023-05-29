<?php

namespace Module\Services\FileStorage\Application\Query;

use Sdk\Module\Contracts\Bus\QueryInterface;

class GetEntityImages implements QueryInterface
{
    public function __construct(
        public readonly string $fileType,
        public readonly ?int $entityId,
    ) {}
}
