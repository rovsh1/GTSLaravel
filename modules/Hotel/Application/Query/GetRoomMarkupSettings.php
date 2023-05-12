<?php

namespace Module\Hotel\Application\Query;

use Custom\Framework\Contracts\Bus\QueryInterface;

class GetRoomMarkupSettings implements QueryInterface
{
    public function __construct(
        public readonly int $roomId
    ) {}
}
