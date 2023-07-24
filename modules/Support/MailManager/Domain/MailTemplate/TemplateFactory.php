<?php

namespace Module\Support\MailManager\Domain\MailTemplate;

use Module\Support\MailManager\Domain\Entity\MailTemplateInterface;

class TemplateFactory
{
    public static function fromKey(TemplatesEnum $key): ?MailTemplateInterface
    {
        return match ($key) {
            TemplatesEnum::CUSTOMER_REGISTRATION => new CustomerRegistration(),
            default => null
        };
    }
}