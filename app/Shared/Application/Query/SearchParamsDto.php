<?php

namespace GTS\Shared\Application\Query;

class SearchParamsDto
{
    public function __construct(
        public readonly int $limit,
        public readonly int $offset,
        public readonly string $orderBy,
        public readonly string $sortOrder
    )
    {
    }
}
