<?php

namespace GTS\Administrator\Application\Query;

use GTS\Shared\Application\Query\QueryInterface;

class GetCurrencies implements QueryInterface
{
    public function __construct(
        public readonly $limit = 10,
        public readonly $offset = 0
    ) {}
}
