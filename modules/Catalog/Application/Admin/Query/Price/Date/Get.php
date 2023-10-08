<?php

namespace Module\Catalog\Application\Admin\Query\Price\Date;

use Sdk\Module\Contracts\Bus\QueryInterface;

class Get implements QueryInterface
{
    public function __construct(
        public readonly int $seasonId,
    ) {}
}
