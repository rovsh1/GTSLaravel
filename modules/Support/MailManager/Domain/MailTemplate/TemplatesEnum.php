<?php

namespace Module\Support\MailManager\Domain\MailTemplate;

enum TemplatesEnum
{
    case CUSTOMER_REGISTRATION;
    case CUSTOMER_PASSWORD_RECOVERY;
    case CUSTOMER_PROFILE_DELETED;
    case REQUEST_FOR_PARTNERSHIP;
    case FEEDBACK_FROM_SITE;

    public function key(): string
    {
        return strtolower(str_replace('_', '-', $this->name));
    }
}