<?php

namespace Module\Support\MailManager\Domain\ValueObject;

enum MailTemplateEnum
{
    case CUSTOMER_REGISTRATION;
    case CUSTOMER_PASSWORD_RECOVERY;
}