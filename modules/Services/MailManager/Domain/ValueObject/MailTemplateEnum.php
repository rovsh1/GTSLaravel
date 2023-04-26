<?php

namespace Module\Services\MailManager\Domain\ValueObject;

enum MailTemplateEnum
{
    case SITE_CUSTOMER_REGISTRATION;
    case SITE_PASSWORD_RECOVERY;
}