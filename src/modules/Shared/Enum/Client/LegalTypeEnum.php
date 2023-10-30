<?php

namespace Module\Shared\Enum\Client;

enum LegalTypeEnum: int
{
    case ONLINE_TRAVEL_AGENCY = 1;
    case TRAVEL_AGENCY = 2;
    case TOUR_OPERATOR = 3;

    public function getKey(): string
    {
        return match ($this) {
            self::ONLINE_TRAVEL_AGENCY => 'OTA',
            self::TRAVEL_AGENCY => 'TA',
            self::TOUR_OPERATOR => 'TO',
        };
    }
}
