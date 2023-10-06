<?php

namespace Module\Pricing\Infrastructure\Service;

use Module\Pricing\Domain\Hotel\ValueObject\RoomId;
use Module\Pricing\Domain\Markup\Service\HotelMarkupFinderInterface;
use Module\Pricing\Domain\Markup\ValueObject\MarkupValue;
use Module\Pricing\Domain\Shared\ValueObject\ClientId;
use Module\Pricing\Infrastructure\Models\MarkupGroup;
use Module\Pricing\Infrastructure\Models\MarkupGroupRule;

class HotelMarkupFinder implements HotelMarkupFinderInterface
{
    private static array $cached = [];

    public function findByRoomId(ClientId $clientId, RoomId $roomId): MarkupValue
    {
        $roomId = $roomId->value();
        if (array_key_exists($roomId, self::$cached)) {
            return self::$cached[$roomId];
        }

        $raw = MarkupGroupRule::findByRoomId($clientId->value(), $roomId)
            ?? MarkupGroup::findByClientId($clientId->value());
        if ($raw === null) {
            throw new \Exception("Client[$clientId] markup undefined");
        }

        return self::$cached[$roomId] = new MarkupValue($raw->value, $raw->type);
    }
}