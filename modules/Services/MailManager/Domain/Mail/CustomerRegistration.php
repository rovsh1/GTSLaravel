<?php

namespace Module\Services\MailManager\Domain\Mail;

class CustomerRegistration extends AbstractMail
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