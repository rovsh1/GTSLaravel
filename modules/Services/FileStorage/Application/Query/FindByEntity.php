<?php

namespace Module\Services\FileStorage\Application\Query;

use Custom\Framework\Contracts\Bus\QueryInterface;

class FindByEntity implements QueryInterface
{
    public function __construct(
        public readonly string $fileType,
        public readonly ?int $entityId,
    ) {}
}