<?php

namespace Module\Services\MailManager\Domain\Factory;

use Module\Services\MailManager\Domain\Entity\MailTemplate;
use Module\Services\MailManager\Domain\Entity\MailTemplateInterface;

class MailTemplateFactory
{
    public static function fromName(string $name, array $data = []): MailTemplateInterface
    {
        return new MailTemplate();
    }
}