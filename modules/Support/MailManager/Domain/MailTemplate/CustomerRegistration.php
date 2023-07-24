<?php

namespace Module\Support\MailManager\Domain\MailTemplate;

class CustomerRegistration extends AbstractMailTemplate
{
    public function description(): string
    {
        return 'Регистрация пользователя на сайте';
    }

    public function category(): string
    {
        return 'site';
    }
}