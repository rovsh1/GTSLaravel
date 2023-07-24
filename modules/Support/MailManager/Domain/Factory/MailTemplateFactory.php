<?php

namespace Module\Support\MailManager\Domain\Factory;

use Module\Support\MailManager\Domain\Entity\MailTemplate;
use Module\Support\MailManager\Domain\Entity\MailTemplateInterface;

class MailTemplateFactory
{
    public static function fromName(string $name, array $data = []): MailTemplateInterface
    {
        return new MailTemplate();
    }
}