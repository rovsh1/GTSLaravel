<?php

namespace Module\Support\NotificationManager\Domain\ValueObject;

enum NotificationEnum
{
    case CUSTOMER_REGISTERED;
    case CUSTOMER_PASSWORD_RECOVERED;
}