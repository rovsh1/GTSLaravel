<?php

namespace Module\Support\MailManager\Infrastructure\Service\RecipientsFinder;

use Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient\RecipientInterface;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\RecipientAddressResolverInterface;
use Module\Support\MailManager\Domain\ValueObject\AddressList;

class AddressResolver implements RecipientAddressResolverInterface
{

    public function resolve(RecipientInterface $recipient): AddressList
    {
        return new AddressList(['s16121986@yandex.ru']);
    }
}