<?php

namespace Module\Services\NotificationManager\Domain\ValueObject;

enum NotificationEnum
{
    case CUSTOMER_REGISTERED;
    case CUSTOMER_PASSWORD_RECOVERED;
}