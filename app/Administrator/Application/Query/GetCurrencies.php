<?php

namespace GTS\Administrator\Application\Query;

use Custom\Framework\Contracts\Bus\QueryInterface;

class GetCurrencies implements QueryInterface
{
    public function __construct(
        public readonly int $limit = 10,
        public readonly int $offset = 0
    ) {}
}
