<?php

namespace Module\Support\MailManager\Infrastructure\Service\RecipientsFinder;

use Module\Support\MailManager\Domain\Service\DataBuilder\Dto\DataDtoInterface;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient\Administrators;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipients;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\RecipientsFinderInterface;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\Template\TemplateInterface;

class RecipientsFinder implements RecipientsFinderInterface
{

    public function findByTemplate(TemplateInterface $template, DataDtoInterface $data): Recipients
    {
        return new Recipients([
            new Administrators()
        ]);
    }
}