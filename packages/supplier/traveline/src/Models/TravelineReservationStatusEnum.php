<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Models;

enum TravelineReservationStatusEnum: string
{
    case NEW = 'new';
    case MODIFIED = 'modified';
    case CANCELLED = 'cancelled';
}
