<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Models;

enum TravelineReservationStatusEnum: string
{
    case New = 'new';
    case Modified = 'modified';
    case Cancelled = 'cancelled';
}
