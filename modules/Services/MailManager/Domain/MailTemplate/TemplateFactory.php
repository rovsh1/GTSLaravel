<?php

namespace Module\Services\MailManager\Domain\MailTemplate;

use Module\Services\MailManager\Domain\Entity\MailTemplateInterface;

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