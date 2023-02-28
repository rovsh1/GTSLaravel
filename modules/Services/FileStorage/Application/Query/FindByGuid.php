<?php

namespace Module\Services\FileStorage\Application\Query;

use Custom\Framework\Contracts\Bus\QueryInterface;

class FindByGuid implements QueryInterface
{
    public function __construct(
        public readonly string $guid
    ) {}
}
