<?php

namespace Supplier\Traveline\Application\Dto\Reservation;

enum StatusEnum: string
{
    case New = 'new';
    case Modified = 'modified';
    case Cancelled = 'cancelled';
}
