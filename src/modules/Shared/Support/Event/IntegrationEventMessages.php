<?php

namespace Module\Shared\Support\Event;

abstract class IntegrationEventMessages
{
    public const BOOKING_STATUS_UPDATED = 'BOOKING_STATUS_UPDATED';
    public const BOOKING_REQUEST_SENT   = 'BOOKING_REQUEST_SENT';
    public const BOOKING_MODIFIED       = 'BOOKING_MODIFIED';
}