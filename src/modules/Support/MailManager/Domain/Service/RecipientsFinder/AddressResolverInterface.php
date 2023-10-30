<?php

namespace Module\Support\MailManager\Domain\Service\RecipientsFinder;

use Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient\RecipientInterface;
use Module\Support\MailManager\Domain\ValueObject\AddressList;

interface AddressResolverInterface
{
    public function resolve(RecipientInterface $recipient): ?AddressList;
}