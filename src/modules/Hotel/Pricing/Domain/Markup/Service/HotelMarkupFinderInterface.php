<?php

declare(strict_types=1);

namespace Module\Hotel\Pricing\Domain\Markup\Service;

use Module\Hotel\Pricing\Domain\Hotel\ValueObject\RoomId;
use Module\Hotel\Pricing\Domain\Shared\ValueObject\ClientId;
use Sdk\Shared\ValueObject\MarkupValue;

interface HotelMarkupFinderInterface
{
    public function findByRoomId(ClientId $clientId, RoomId $roomId): MarkupValue;
}
