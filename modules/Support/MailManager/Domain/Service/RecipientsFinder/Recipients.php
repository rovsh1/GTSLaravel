<?php

namespace Module\Support\MailManager\Domain\Service\RecipientsFinder;

use Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient\RecipientInterface;
use Sdk\Module\Support\AbstractItemCollection;

final class Recipients extends AbstractItemCollection
{
    protected function validateItem($item): void
    {
        if (!$item instanceof RecipientInterface) {
            throw new \Exception('Recipient not valid');
        }
    }
}