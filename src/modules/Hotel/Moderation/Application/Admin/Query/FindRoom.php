<?php

namespace Module\Hotel\Moderation\Application\Admin\Query;

use Sdk\Module\Contracts\Bus\QueryInterface;

class FindRoom implements QueryInterface
{
    public function __construct(
        public readonly int $id
    ) {}
}