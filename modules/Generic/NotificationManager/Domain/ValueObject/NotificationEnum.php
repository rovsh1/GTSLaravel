<?php

namespace Module\Generic\NotificationManager\Domain\ValueObject;

enum NotificationEnum
{
    case CUSTOMER_REGISTERED;
    case CUSTOMER_PASSWORD_RECOVERED;
}