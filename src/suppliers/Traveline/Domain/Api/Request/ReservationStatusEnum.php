<?php

namespace Supplier\Traveline\Domain\Api\Request;

enum ReservationStatusEnum: string
{
    case New = 'new';
    case Modified = 'modified';
    case Cancelled = 'cancelled';
}
