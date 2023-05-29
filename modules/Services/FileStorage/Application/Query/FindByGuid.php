<?php

namespace Module\Services\FileStorage\Application\Query;

use Sdk\Module\Contracts\Bus\QueryInterface;

class FindByGuid implements QueryInterface
{
    public function __construct(
        public readonly string $guid
    ) {}
}
