<?php

namespace Module\Support\MailManager\Infrastructure\Service\RecipientsFinder;

use Module\Support\MailManager\Domain\Service\DataBuilder\Dto\DataDtoInterface;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\RecipientFactory;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipients;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\RecipientsFinderInterface;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\Template\TemplateInterface;
use Module\Support\MailManager\Infrastructure\Model\Recipient;

class RecipientsFinder implements RecipientsFinderInterface
{
    public function findByTemplate(TemplateInterface $template, DataDtoInterface $data): Recipients
    {
        $recipients = Recipient::whereTemplate($template->key())
            ->get()
            ->map(fn(Recipient $r) => RecipientFactory::fromKey($r->recipient_type, $r->recipient_id))
            ->all();

        return new Recipients($recipients);
    }
}