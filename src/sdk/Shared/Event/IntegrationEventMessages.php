<?php

namespace Sdk\Shared\Event;

abstract class IntegrationEventMessages
{
    public const BOOKING_STATUS_UPDATED   = 'BOOKING_STATUS_UPDATED';
    public const BOOKING_REQUEST_SENT     = 'BOOKING_REQUEST_SENT';
    public const BOOKING_MODIFIED         = 'BOOKING_MODIFIED';
    public const BOOKING_DETAILS_MODIFIED = 'BOOKING_DETAILS_MODIFIED';
}