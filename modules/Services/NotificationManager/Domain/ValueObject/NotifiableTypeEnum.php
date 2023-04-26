<?php

namespace Module\Services\NotificationManager\Domain\ValueObject;

enum NotifiableTypeEnum
{
    case ADMINISTRATOR;
    case ADMINISTRATOR_GROUP;
    case CLIENT_CONTACTS;
    case CLIENT_MANAGERS;
    case EMAIL_ADDRESS;
//    case HOTEL_ADMINISTRATOR;
    case HOTEL_ADMINISTRATORS;
    case HOTEL_CONTACTS;
    case USER;
}