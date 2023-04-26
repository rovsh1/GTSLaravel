<?php

namespace Module\Services\MailManager\Domain\ValueObject;

enum MailTemplateEnum
{
    case CUSTOMER_REGISTRATION;
    case CUSTOMER_PASSWORD_RECOVERY;
}