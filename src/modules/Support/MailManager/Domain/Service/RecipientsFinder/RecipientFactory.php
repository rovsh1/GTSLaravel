<?php

namespace Module\Support\MailManager\Domain\Service\RecipientsFinder;

use Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient\RecipientInterface;

abstract class RecipientFactory
{
    private static array $recipientsClasses = [
        Recipient\Administrator::class,
        Recipient\AdministratorGroup::class,
        Recipient\Administrators::class,
        Recipient\Client::class,
        Recipient\Email::class,
        Recipient\Hotel::class,
        Recipient\HotelContacts::class,
        Recipient\HotelEmployees::class,
        Recipient\HotelManagers::class,
        Recipient\User::class,
    ];

    public static function fromKey(string $key, string $id = null): RecipientInterface
    {
        foreach (self::$recipientsClasses as $class) {
            if ($class::key() === $key) {
                return new $class($id);
            }
        }

        throw new \Exception("Cant create recipient by key $key");
    }
}