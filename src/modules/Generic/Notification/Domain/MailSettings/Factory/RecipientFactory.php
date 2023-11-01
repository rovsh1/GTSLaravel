<?php

namespace Module\Generic\Notification\Domain\MailSettings\Factory;

use Module\Generic\Notification\Domain\MailSettings\ValueObject\RecipientTypeEnum;
use Module\Generic\Notification\Domain\MailSettings\ValueObject\Recipient\Administrator;
use Module\Generic\Notification\Domain\MailSettings\ValueObject\Recipient\AdministratorGroup;
use Module\Generic\Notification\Domain\MailSettings\ValueObject\Recipient\ClientContacts;
use Module\Generic\Notification\Domain\MailSettings\ValueObject\Recipient\ClientManagers;
use Module\Generic\Notification\Domain\MailSettings\ValueObject\Recipient\Customer;
use Module\Generic\Notification\Domain\MailSettings\ValueObject\Recipient\EmailAddress;
use Module\Generic\Notification\Domain\MailSettings\ValueObject\Recipient\HotelAdministrators;
use Module\Generic\Notification\Domain\MailSettings\ValueObject\Recipient\HotelContacts;
use Module\Generic\Notification\Domain\MailSettings\ValueObject\Recipient\RecipientInterface;

class RecipientFactory
{
    public function fromPayload(array $payload): RecipientInterface
    {
        return match (RecipientTypeEnum::from($payload['type'])) {
            RecipientTypeEnum::EMAIL_ADDRESS => new EmailAddress($payload['email'], $payload['presentation']),
            RecipientTypeEnum::ADMINISTRATOR => new Administrator($payload['administratorId']),
            RecipientTypeEnum::ADMINISTRATOR_GROUP => new AdministratorGroup($payload['groupId']),
            RecipientTypeEnum::CLIENT_CONTACTS => new ClientContacts(),
            RecipientTypeEnum::CLIENT_MANAGERS => new ClientManagers(),
            RecipientTypeEnum::HOTEL_ADMINISTRATORS => new HotelAdministrators(),
            RecipientTypeEnum::HOTEL_CONTACTS => new HotelContacts(),
            RecipientTypeEnum::CUSTOMER => new Customer(),
        };
    }
}