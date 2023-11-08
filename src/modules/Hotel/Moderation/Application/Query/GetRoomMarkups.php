<?php

namespace Module\Hotel\Moderation\Application\Query;

use Sdk\Module\Contracts\Bus\QueryInterface;

class GetRoomMarkups implements QueryInterface
{
    public function __construct(
        public readonly int $roomId
    ) {}
}
