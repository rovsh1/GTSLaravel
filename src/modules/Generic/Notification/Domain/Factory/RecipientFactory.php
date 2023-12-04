<?php

namespace Module\Generic\Notification\Domain\Factory;

use Module\Generic\Notification\Domain\Enum\RecipientTypeEnum;
use Module\Generic\Notification\Domain\Recipient\Administrator;
use Module\Generic\Notification\Domain\Recipient\AdministratorGroup;
use Module\Generic\Notification\Domain\Recipient\ClientContacts;
use Module\Generic\Notification\Domain\Recipient\ClientManagers;
use Module\Generic\Notification\Domain\Recipient\Customer;
use Module\Generic\Notification\Domain\Recipient\EmailAddress;
use Module\Generic\Notification\Domain\Recipient\HotelAdministrators;
use Module\Generic\Notification\Domain\Recipient\HotelContacts;
use Module\Generic\Notification\Domain\Recipient\RecipientInterface;

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