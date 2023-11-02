<?php

namespace Module\Generic\Notification\Domain\MailSettings\ValueObject;

enum RecipientTypeEnum: int
{
    case ADMINISTRATOR = 1;
    case ADMINISTRATOR_GROUP = 2;
    case CLIENT_CONTACTS = 3;
    case CLIENT_MANAGERS = 4;
    case EMAIL_ADDRESS = 5;
//    case HOTEL_ADMINISTRATOR;
    case HOTEL_ADMINISTRATORS = 6;
    case HOTEL_CONTACTS = 7;
    case CUSTOMER = 8;
}