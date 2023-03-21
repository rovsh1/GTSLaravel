<?php

namespace Module\Hotel\Application\Query;

use Custom\Framework\Contracts\Bus\QueryInterface;

class GetRoomById implements QueryInterface
{
    public function __construct(
        public readonly int $id
    ) {}
}
