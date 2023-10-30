<?php

namespace Module\Support\MailManager\Domain\Service\RecipientsFinder;

use Module\Support\MailManager\Domain\Service\DataBuilder\Dto\DataDtoInterface;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\Template\TemplateInterface;

interface RecipientsFinderInterface
{
    public function findByTemplate(TemplateInterface $template, DataDtoInterface $data): Recipients;
}