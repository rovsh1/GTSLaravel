<?php

namespace Module\Shared\Enum\Client;

enum LegalTypeEnum: string
{
    case ONLINE_TRAVEL_AGENCY = 'OTA';
    case TRAVEL_AGENCY = 'TA';
    case TOUR_OPERATOR = 'TO';
}
