<?php

namespace GTS\Administrator\Application\Query;

use Custom\Framework\Contracts\Bus\QueryInterface;

class FindByIdWithTranslations implements QueryInterface
{
    public function __construct(
        public readonly int $id
    ) {}
}
