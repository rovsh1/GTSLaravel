<?php

namespace Module\Integration\Traveline\Infrastructure\Models\Legacy;

enum TravelineReservationStatusEnum: string
{
    case New = 'new';
    case Modified = 'modified';
    case Cancelled = 'cancelled';
}
