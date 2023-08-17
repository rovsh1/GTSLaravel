<?php

namespace Module\Support\MailManager\Domain\Service\RecipientsFinder;

use Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient\RecipientInterface;
use Module\Support\MailManager\Domain\ValueObject\AddressList;

interface RecipientAddressResolverInterface
{
    public function resolve(RecipientInterface $recipient): AddressList;
}