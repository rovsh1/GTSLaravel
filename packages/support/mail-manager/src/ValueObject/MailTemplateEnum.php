<?php

namespace Pkg\MailManager\ValueObject;

enum MailTemplateEnum
{
    case CUSTOMER_REGISTRATION;
    case CUSTOMER_PASSWORD_RECOVERY;
}