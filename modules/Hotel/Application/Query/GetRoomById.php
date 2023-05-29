<?php

namespace Module\Hotel\Application\Query;

use Sdk\Module\Contracts\Bus\QueryInterface;

class GetRoomById implements QueryInterface
{
    public function __construct(
        public readonly int $id
    ) {}
}
