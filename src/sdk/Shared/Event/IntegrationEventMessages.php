<?php

namespace Sdk\Shared\Event;

abstract class IntegrationEventMessages
{
    public const BOOKING_STATUS_UPDATED                     = 'BOOKING_STATUS_UPDATED';
    public const BOOKING_PRICE_CHANGED                      = 'BOOKING_PRICE_CHANGED';
    public const BOOKING_REQUEST_SENT                       = 'BOOKING_REQUEST_SENT';
    public const BOOKING_MODIFIED                           = 'BOOKING_MODIFIED';
    public const BOOKING_DETAILS_MODIFIED                   = 'BOOKING_DETAILS_MODIFIED';
    public const HOTEL_BOOKING_GUEST_ADDED                  = 'HOTEL_BOOKING_GUEST_ADDED';
    public const HOTEL_BOOKING_GUEST_REMOVED                = 'HOTEL_BOOKING_GUEST_REMOVED';
    public const HOTEL_BOOKING_ACCOMMODATION_ADDED          = 'HOTEL_BOOKING_ACCOMMODATION_ADDED';
    public const HOTEL_BOOKING_ACCOMMODATION_REMOVED        = 'HOTEL_BOOKING_ACCOMMODATION_REMOVED';
    public const HOTEL_BOOKING_ACCOMMODATION_DETAILS_EDITED = 'HOTEL_BOOKING_ACCOMMODATION_DETAILS_EDITED';
    public const HOTEL_BOOKING_ACCOMMODATION_REPLACED       = 'HOTEL_BOOKING_ACCOMMODATION_REPLACED';
    public const TRANSFER_BOOKING_CAR_BID_ADDED             = 'TRANSFER_BOOKING_CAR_BID_ADDED';
    public const TRANSFER_BOOKING_CAR_BID_REMOVED           = 'TRANSFER_BOOKING_CAR_BID_REMOVED';
    public const TRANSFER_BOOKING_CAR_BID_UPDATED           = 'TRANSFER_BOOKING_CAR_BID_UPDATED';
    public const AIRPORT_BOOKING_GUEST_ADDED                = 'AIRPORT_BOOKING_GUEST_ADDED';
    public const AIRPORT_BOOKING_GUEST_REMOVED              = 'AIRPORT_BOOKING_GUEST_REMOVED';
}